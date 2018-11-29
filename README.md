# Assigner

Assigner works recursively, so it is easy to transform nested array to nested objects.
Supports Laravel Collections.

This package will be usefull for:
1. parsing json to objects
2. fast recursive assign object properties
3. DTO

## Installation

    composer require php-utils/assigner

## Usage

Everything you need to do is:
```
class YourClass implements Assigner\Contracts\Assignable {
    use Assigner\Traits\Assigner;
}
```

## Run Example

./example.sh

### Example:
```
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

$person = new Person;
$person->assign(json_decode($input, true));

dump($person);

// OUTPUT
//
// Person {#2
//  -firstName: "John"
//  -lastName: "Doe"
//  -address: Address {#4
//    -city: "London"
//    -street: "Baker"
//  }
//  -friends: Assigner\Collection {#5
//    #items: array:2 [
//      0 => Person {#12
//        -firstName: "Jack"
//        -lastName: "London"
//        -address: Address {#13
//          -city: "Liverpool"
//          -street: "Green"
//        }
//        -friends: Assigner\Collection {#14
//          #items: []
//        }
//      }
//      1 => Person {#16
//        -firstName: "Mary"
//        -lastName: "Simpson"
//        -address: Address {#17
//          -city: "Springfield"
//          -street: "Grey"
//        }
//        -friends: Assigner\Collection {#18
//          #items: array:1 [
//            0 => Person {#24
//              -firstName: "Brad"
//              -lastName: "Brown"
//              -address: Address {#25
//                -city: null
//                -street: null
//              }
//              -friends: Assigner\Collection {#26
//                #items: []
//              }
//            }
//          ]
//        }
//      }
//    ]
//  }
//}
