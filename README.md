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
```
###Output:
````
print_r($foo->toArray());

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

print_r($foo);

/*
 * object(Foo)#2 (3) {
 *       ["name":"Foo":private]=>
 *       string(3) "foo"
 *       ["bars":"Foo":private]=>
 *       object(Assigner\Collection)#5 (2) {
 *         ["objectMacros":protected]=>
 *         array(1) {
 *           ["create"]=>
 *           object(Closure)#6 (2) {
 *             ["static"]=>
 *             array(1) {
 *               ["class"]=>
 *               string(3) "Bar"
 *             }
 *             ["this"]=>
 *             *RECURSION*
 *           }
 *         }
 *         ["items":protected]=>
 *         array(2) {
 *           [0]=>
 *           object(Bar)#9 (1) {
 *             ["name":"Bar":private]=>
 *             string(7) "bar one"
 *           }
 *           [1]=>
 *           object(Bar)#7 (1) {
 *             ["name":"Bar":private]=>
 *             string(7) "bar two"
 *           }
 *         }
 *       }
 *       ["baz":"Foo":private]=>
 *       object(Baz)#4 (1) {
 *         ["name":"Baz":private]=>
 *         string(3) "baz"
 *       }
 *     }
 */
 
