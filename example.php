<?php

require_once './vendor/autoload.php';

use Assigner\Contracts\Assignable;
use Assigner\Traits\Assigner;

class Address implements Assignable {
    use Assigner;

    private $city;
    private $street;
}

class Person implements Assignable {
    use Assigner;

    private $firstName;
    private $lastName;
    private $address;
    private $friends;

    public function __construct()
    {
        $this->address = new Address;
        $this->initCollection('friends', static::class);
    }
}

$input = <<<JSN
{
  "first_name": "John",
  "last_name": "Doe",
  "address": {
    "city": "London",
    "street": "Baker"
  },
  "friends": [
    {
      "first_name": "Jack",
      "last_name": "London",
      "address": {
        "city": "Liverpool",
        "street": "Green"
      }
    },
    {
      "first_name": "Mary",
      "last_name": "Simpson",
      "address": {
        "city": "Springfield",
        "street": "Grey"
      },
      "friends":  [
        {
          "first_name": "Brad",
          "last_name":  "Brown"
        }
      ]
    }
  ]
}
JSN;

$inputArray = json_decode($input, true);

$person = new Person;
$person->assign($inputArray);

dump('===========   INPUT   ===========');
print_r(json_encode($inputArray, JSON_PRETTY_PRINT).PHP_EOL);
dump('===========   OUTPUT   ===========');
dump($person);
