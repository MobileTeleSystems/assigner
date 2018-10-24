# Assigner

Assigner works recursively, so it is easy to transform nested array to nested objects.

This package will be usefull for:
1. parsing json to objects
2. fast assign object properties
3. DTO

###Example:
```

class Foo implements Assignable
{
    use Assigner;

    private $firstName;
    private $theBars;

    public function __construct()
    {
        $this->initCollection('theBars', Bar::class);
    }
}

class Bar implements Assignable
{
    use Assigner;

    private $mainOption;
    private $baz;

    public function __construct()
    {
        $this->baz = new Baz;
    }
}

class Baz implements Assignable
{
    use Assigner;

    private $firstValue;
    private $lastScore;
}

$input = [
    'first_name' => 'foo',
    'the_bars' => [
        [
            'main_option' => 'first',
            'baz' => [
                'first_value' => 1,
                'last_score' => 0
            ]
        ],
        [
            'main_option' => 'second',
            'baz' => [
                'first_value' => 2,
                'last_score' => 9
            ]
        ]
    ]
];

$foo = new Foo;
$foo->assign($input);

print_r($foo);

// Array
//(
//    [name] => foo
//    [bars] => Array
//        (
//            [0] => Array
//                (
//                    [option] => first
//                    [baz] => Array
//                        (
//                            [value] => 1
//                            [score] => 0
//                        )
//
//                )
//
//            [1] => Array
//                (
//                    [option] => second
//                    [baz] => Array
//                        (
//                            [value] => 2
//                            [score] => 9
//                        )
//
//                )
//
//        )
//
//)
