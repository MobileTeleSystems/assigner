# Assigner

Assigner works recursively, so it is easy to transform nested array to nested objects.

This package will be usefull for:
1. parsing json to objects
2. fast assign object properties
3. DTO

###Example:
```
use Illuminate\Contracts\Support\Arrayable;
use Assigner\{Assignable, Assigner, ToArray};

class Bar implements Arrayable, Assignable
{
    use Assigner, ToArray;

    private $name;
}

class Baz implements Arrayable, Assignable
{
    use Assigner, ToArray;

    private $name;
}

class Foo implements Arrayable, Assignable
{
    use Assigner, ToArray;

    private $name;
    private $bars;
    private $baz;

    public function __construct()
    {
        $this->baz = new Baz;
        $this->initCollection('bars', Bar::class);
    }
}

$raw = [
    'name' => 'foo',
    'baz'  => [
        'name' => 'baz'
    ],
    'bars' => [
        [
            'name' => 'bar one'
        ],
        [
            'name' => 'bar two'
        ]
    ]
];

$foo = new Foo();
$foo->assign($raw);
print_r($foo->toArray());
```
###Output:
````
/*
 * Array
 * (
 *     [name] => foo
 *     [bars] => Array
 *         (
 *             [0] => Array
 *                 (
 *                     [name] => bar one
 *                 )
 * 
 *             [1] => Array
 *                 (
 *                     [name] => bar two
 *                 )
 * 
 *        )
 * 
 *     [baz] => Array
 *         (
 *             [name] => baz
 *         )
 * 
 * )
 */
 ```