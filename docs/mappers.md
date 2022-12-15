# Mappers

This will allow you to map strings to existing enums. This is useful when you
for example are building against multiple third party API's and you need to
translate the enums between one and other.

Note: `Mappers` is the only one that you cannot use together with
`Getters`, `Extractor` and `Reporters`. This is simply due to the fact that it is
implementing the methods for those by itself. Under the hood it's using their
functionality, so the methods will work just the same.

## Usage

```php
use Henzeb\Enumhancer\Concerns\Mappers;


enum YourEnum {
    use Mappers;

    case ENUM;
    case NO_LABEL;
    case NOT_MAPPED;
}
```

```php
use Henzeb\Enumhancer\Contracts\Mapper;

class YourMapper extends Mapper {

    public function mappable() : array
    {
         return [
            'Mapped' => YourEnum::ENUM,
            'LABEL_MISSING' => 'NO_LABEL'
         ];
    }
}
```

### Examples

You can either use your instantiated `YourMapper` or just the
FQCN `YourMapper::class`.

```php
/** get */
YourEnum::get('Mapped', YourMapper::class); // will return YourEnum::ENUM
YourEnum::get('NOT_MAPPED', new YourMapper()); // will return YourEnum::NOT_MAPPED
YourEnum::get('unknown', YourMapper::class); // will throw exception

/** tryGet */
YourEnum::tryGet('Mapped', YourMapper::class); // will return YourEnum::ENUM
YourEnum::tryGet('NOT_MAPPED', new YourMapper()); // will return YourEnum::NOT_MAPPED
YourEnum::tryGet('unknown', YourMapper::class); // will return null
/** getArray */
YourEnum::getArray(
    ['Mapped', 'NOT_MAPPED'],
    YourMapper::class
); // will return [YourEnum::ENUM, YourEnum::NOT_MAPPED]

YourEnum::getArray(
    ['unknown', 'NOT_MAPPED'],
    new YourMapper()
); // will throw exception

/** tryArray */
YourEnum::tryArray(
    ['Mapped', 'NOT_MAPPED'],
    YourMapper::class
); // will return [YourEnum::ENUM, YourEnum::NOT_MAPPED]

YourEnum::tryArray(
    ['unknown', 'NOT_MAPPED'],
    new YourMapper()
); // will return [YourEnum::NOT_MAPPED]

/** getOrReport */
YourEnum::getOrReport(
    ['Mapped', 'NOT_MAPPED'],
    YourMapper::class
); // will return [YourEnum::ENUM, YourEnum::NOT_MAPPED]

YourEnum::getOrReport(
    ['unknown', 'NOT_MAPPED'],
    new YourMapper()
); // will return [YourEnum::NOT_MAPPED]


/** extract */
YourEnum::extract(
'a sentence with Mapped in it',
    YourMapper::class
); // will return [YourEnum::ENUM]

YourEnum::extract(
'a sentence with Mapped in it',
    new YourMapper()
); // will return [YourEnum::ENUM]
```

Note: See for the `getOrReport` method: [Reporters](reporters.md)
Note: See for the `extract` method: [Extractor](extractor.md)

### Shared Mapper

You can also use one `Mapper` for multiple enums. Just use the FQCN of the enum
as a key in your array, like below:

```php
use Henzeb\Enumhancer\Contracts\Mapper;

class YourMapper extends Mapper {

    public function mappable() : array
    {
         return [
            YourEnum::class => [
                'Mapped' => YourEnum::ENUM,
                'LABEL_MISSING' => 'NO_LABEL'
            ]
         ];
    }
}
```

And then use the commands as shown in the `example` section.

## The mapper method

In case you don't want to add the mapper all the time, you can also specify
a `mapper` method that returns your mapper.

```php
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;

enum YourEnum {
    use Mappers;

    case ENUM;
    case NO_LABEL;
    case NOT_MAPPED;

    protected static mapper(): ?Mapper
    {
        return new YourMapper();
    }
}
```

## Mapping with arrays

You can specify a map just by passing an array. This way you don't
have to create a `Mapper` class.

```php
use Henzeb\Enumhancer\Concerns\Mappers;

enum Suit {
use Mappers, Getters;

    case Spades;
    case Diamonds;

    public static function mapper() : array
    {
        return [
            'schoppen' => self::Spades,
            'ruiten' => self::Diamonds
        ];
    }
}

Suit::get('schoppen'); // returns self::Spades
Suit::get('ruiten'); // returns self::Diamonds
```

## Mapping with a constant

You can also specify a map inside a constant. This way you don't
need to add a static `mapper` method and this allows you to use
[Configurable](configure.md#configuremapper) for mappers.

```php
use Henzeb\Enumhancer\Concerns\Mappers;

enum Suit {
    use Mappers, Getters;

    case Spades;
    case Diamonds;

    private const MAP_SPADES = [
        'schoppen' => self::Spades
    ];

    private const map_diamonds = [
        'ruiten' => self::Diamonds
    ];
}

Suit::get('schoppen'); // returns self::Spades
Suit::get('ruiten'); // returns self::Diamonds
```

## Flip

In some cases you might want to map two enums to each other. This is already
possible by making a shared mapper with prefix, but this makes it a lot simpler.

````php
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;

class AnimalMapper extends Mapper
{
    public function mappable(): array
    {
        return [
            AnimalLatin::Canine->name => Animal::Dog,
            AnimalLatin::Feline->name => Animal::Cat,
        ];
    }
}

enum Animal
{
    use Mappers;

    case Dog;
    case Cat;

    protected static function mapper(): Mapper
    {
        return new AnimalMapper();
    }
}

enum LatinAnimalName
{
    use Mappers, From, Comparison;

    case Canine;
    case Feline;

    public static function mapper(): Mapper
    {
        return AnimalMapper::flip();
    }
}

Animal::get('Canine'); // Animal::Dog
LatinAnimalName::get('Dog') // Animal::Canine

Animal::get(LatinAnimalName::Feline); // Animal::Cat
LatinAnimalName::get(Animal::Cat) // LatinAnimalName::Feline

/** with From */
Animal::from(LatinAnimalName::Feline); // Animal::Cat
LatinAnimalName::from(Animal::Cat) // LatinAnimalName::Feline

Animal::from('Feline'); // throws ValueError
````

### Flip with shared mappers

When you use shared mappers, you can specify the prefix, which is generally
the FQCN of the enum you want to use it with.

````php
AnimalMapper::flip(LatinAnimalName::class);
````

