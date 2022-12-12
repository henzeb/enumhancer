# Comparison

This will allow you to easily compare enums, integers and strings with each
other. This also is backwards-compatible with
[Spatie's PHP Enum](https://github.com/spatie/enum)

## usage

```php
use Henzeb\Enumhancer\Concerns\Comparison;

enum YourEnum: string {
    use Comparison;

    CASE ENUM = 'your_value';
    CASE ENUM2 = 'your_other_value';
}

enum YourOtherEnum: int {
    use Comparison;

    CASE ENUM = 0;
    CASE ENUM2 = 1;
}

enum YourThirdEnum {
    use Comparison;

    CASE ENUM;
    CASE ENUM2;
}
```

### Examples

The method equals accepts multiple strings, integers or enums of the same type.
If one of them matches, true will be returned.

```php
YourEnum::ENUM->equals(YourEnum::ENUM); // returns true
YourEnum::ENUM->equals('ENUM'); // returns true
YourEnum::ENUM->equals('your_value'); //returns true

YourEnum::ENUM->equals(YourEnum::ENUM2); // returns false
YourEnum::ENUM->equals('ENUM2'); // returns false
YourEnum::ENUM->equals('your_other_value'); //returns false

YourEnum::ENUM->equals(YourEnum::ENUM, 'your_other_value'); // returns true
YourEnum::ENUM->equals('ENUM', YourEnum::ENUM2); // returns true
YourEnum::ENUM->equals('your_value', 'your_other_value'); //returns true

YourOtherEnum::ENUM->equals(YourOtherEnum::ENUM); // returns true
YourOtherEnum::ENUM->equals(0); // returns true
YourOtherEnum::ENUM->equals(1); //returns false
YourOtherEnum::ENUM->equals(0, 1); //returns true

YourThirdEnum::ENUM->equals(YourThirdEnum::ENUM); // returns true
YourThirdEnum::ENUM->equals('ENUM'); // returns true
YourThirdEnum::ENUM->equals('ENUM2'); //returns false
YourThirdEnum::ENUM->equals('enum'); // returns true
YourThirdEnum::ENUM->equals('enum2'); //returns false
YourThirdEnum::ENUM->equals('enum', 'enum2'); //returns true
```

## Assertions

Next to `equals`, you can also handle assertions with `is` and `isNot`.

```php
YourEnum::ENUM->isEnum(); // returns true
YourEnum::ENUM->isNotEnum(); // returns false
YourEnum::ENUM->isEnum2(); // returns false
YourEnum::ENUM->isNotEnum2(); // returns true
YourEnum::ENUM->isYour_Value(); //returns true

YourOtherEnum::ENUM->isEnum(); // returns true
YourOtherEnum::ENUM->is0(); // returns true
YourOtherEnum::ENUM->isNot0(); //returns false;

YourThirdEnum::ENUM->isEnum(); // returns true
YourThirdEnum::ENUM->isNotEnum(); // returns false
```

Note: When a case name or value contains an underscore, your method has to
contain that underscore. You also cannot use values with spaces.

Tip: Use the @method tag in your docblock to typehint the methods if you like.

## Comparing and mapping

Comparison automatically uses [Mappers](mappers.md) whenever available.

````php
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\Comparison;
use Henzeb\Enumhancer\Contracts\Mapper;

enum Animal
{
    use Mappers, Comparison;

    case Dog;
    case Cat;

    protected static function mapper(): ?Mapper
    {
        return new AnimalMapper();
    }
}

enum LatinAnimalName
{
    case Canine;
    case Feline;
}

Animal::Dog->isCanine(); // returns true;
Animal::Dog->isFeline(); // returns false;
Animal::Dog->isSomething(); // throws error
````

You can even match with enum cases that are not part of the same enum
object, but do match by name or are mapped using a mapper.

````php
Animal::Dog->equals(LatinAnimalName::Canine); // returns true;
Animal::Dog->equals(LatinAnimalName::Feline); // returns false;

Animal::Dog->equals(SomeOtherEnum::Canine); // return true
Animal::Dog->equals(SomeOtherEnum::Dog); // return true
Animal::Dog->equals(SomeOtherEnum::Something); // throws error
````
