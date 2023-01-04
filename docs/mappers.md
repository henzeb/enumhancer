# Mappers

This will allow you to map strings to existing enums. This is useful when you
for example are building against multiple third party API's and you need to
translate the enums between one and other.

Note: `Mappers` is the only one that you cannot use together with
`Getters`, `Extractor` and `Reporters`. This is simply due to the fact that it is
implementing the methods for those by itself. Under the hood it's using their
functionality, so the methods will work just the same.

## Usage

### simple constant definition

Just like with [Defaults](defaults.md), constants can be used to map a reference
to an existing enum key. When such a constant exists, Enumhancer treats it like any
other case.

````php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Mappers;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;

    const AceOfClubs = Suit::Clubs;
}

Suit::get('AceOfClubs'); //returns Suit::Clubs

````

Note: constants containing a reference to existing cases can also be set to `private`
to prevent direct reference.

### constant definitions

You can also add a constant definition with an array inside. The name
should be starting with `map`, either lower or uppercase.

````php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Mappers;

    private const MAP_SUIT = [
        'AceOfSpades' => Suit::Spades
    ];

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;
}
Suit::get('AceOfSpades'); //returns Suit::Spades
````

You can also have multiple `mappers` specified that way. Just add another constant.

### The Mapper object

When you plan to use a different mapper for different situations, like languages,
You can create an object that extends `Henzeb\Enumhancer\Contracts\Mapper`.

````php
class SuitMapper extends Henzeb\Enumhancer\Contracts\Mapper
{
    protected function mappable(): array
    {
        return [
            'AceOfHearts' => 'hearts',
            'AceOfClubs' => 'Clubs',
            'AceOfSpades' => 2,
            'AceOfDiamonds' => Suit::Diamonds
        ];
    }
}
````

Just like cases, keys and values don't have to be in the correct case.

#### Using constants

Just like arrays, when a constant has a string that points to an actual
`Mapper`, Enumhancer will transform it into a Mapper object and use this.

````php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Mappers;

    private const MAP_SUIT = SuitMapper::class;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;
}

Suit::get('AceOfDiamonds'); //returns Suit::Diamonds
````

#### Using a static method

In some cases you might want to apply the mapper through a method.

````php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Mappers;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;

    protected static function mapper(): Henzeb\Enumhancer\Contracts\Mapper|array|string|null
    {
        return SuitMapper::class;
    }
}

Suit::get('AceOfHearts'); // returns Suit::Hearts
````

#### add as parameter

If you wish, you can add as many mappers as you like to any of the
[Getters](getters.md) methods.

````php
Suit::get('AceOfHearts', SuitMapper::class, SuitMapperFrench::class);
Suit::get('AceOfHearts', SuitMapper::getNewInstance());
Suit::tryGet('AceOfHearts', ['AceOfClubs'=>'AceOfHearts']);
````

#### using Configure

See [Configure](configure.md#configuremapper)

#### Combining the different ways

It's possible to combine the different methods. Mapping values is done waterfall.
This means a mapper can map to a value that does not necessarily exist in the `enum`
object, so another mapper can map that value to the final case.

````php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Mappers;

    private const MAP_SUIT = [
        'AceOfHearts' => 'AceOfClubs'
    ];

    private const MAP_FINAL = [
        'AceOfClubs' => Suit::Spades
    ];

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;
}

Suit::get('AceOfHearts'); //returns Suit::Spades
````

The order of precedence is as follows:

- Any mappers passed by argument in order of appearance
- Mapper returned by [static method](#using-a-static-method)
- Mappers set by [Configure](configure.md#configuremapper)
- Mappers (array or class name) defined in constants in order
 of appearance

#### Shared mappers

The mapper object allows you to share one single object for multiple
enums. For that to work, you have to return a multidimensional array
that has a key pointing at your enum containing an array of what you
want to map for this particular enum.

````php
class SuitMapper extends Henzeb\Enumhancer\Contracts\Mapper
{
    protected function mappable(): array
    {
        return [
            Suit::class => [
                'AceOfHearts' => 'hearts',
                'AceOfClubs' => 1,
                'AceOfSpades' => 'Spades',
            ],
            'AceOfDiamonds' => Suit::Diamonds
        ];
    }
}
````

#### Defined

If, for some reason, you want to know if a value is defined,
you can check that with this method.

````php
$suitMapper->defined('AceOfDiamonds'); // returns true
$suitMapper->defined('AceOfDiamonds', Suit::class); // returns true

$suitMapper->defined('AceOfHearts'); // returns false
$suitMapper->defined('AceOfHearts', Suit::class); // returns true

SuitMapper::defined('AceOfDiamonds'); // returns true
SuitMapper::defined('AceOfDiamonds', Suit::class); // returns true

SuitMapper::defined('AceOfHearts'); // returns false
SuitMapper::defined('AceOfHearts', Suit::class); // returns true
````

#### Keys

The `keys` method shows you a list of defined keys.

In case of a shared mapper, without the enum class name, `keys`
will only return global map keys.

````php
$suitMapper->keys();
$suitMapper->keys(Suit::class);
SuitMapper::keys();
SuitMapper::keys(Suit::class);
````

#### Flip

In some cases, you might want to flip your map. Instead of
creating a second mapper, you can just call flip on your existing
mapper.

In case of a shared mapper, without the enum class name, only
global map keys will be flipped and used for mapping.

````php
$suitMapper->flip();
$suitMapper->flip(Suit::class);
SuitMapper::flip();
SuitMapper::flip(Suit::class);
````

Just like with `MAP`, you can add constants starting with
`MAP_FLIP` (again, uppercased or lowercased) to point at your mapper.

````php
enum Suit
{
    use Henzeb\Enumhancer\Concerns\Mappers;

    private const MAP_FLIP_SUIT = SuitMapper::class;

    case Hearts;
    case Clubs;
    case Spades;
    case Diamonds;
}
````
