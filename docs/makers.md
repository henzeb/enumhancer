# Makers

DEPRECATED. PLEASE USE [Getters](getters.md) instead!

Enums have out of the box the methods `from` and `tryFrom`. One drawback is that
you cannot use them to create `enum objects` with the name of the `enum`.

This allows you to do so.

Note: the name `make` is chosen because of Spatie, who started using them and
never really moved away from them. So this is also backwards compatible with
[Spatie's PHP Enum](https://github.com/spatie/enum)

## Usage

```php
use Henzeb\Enumhancer\Concerns\Makers;

enum YourEnum: string {
    use Makers;

    case ENUM = 'your_enum';
    case ENUM2 = 'your_other_enum';
}
```

### Examples

```php
/** make */
YourEnum::make('ENUM'); // returns YourEnum::ENUM
YourEnum::make('0'); // returns YourEnum::ENUM
YourEnum::make(0); // returns YourEnum::ENUM
YourEnum::make('ENUM2'); // returns YourEnum::ENUM2
YourEnum::make('1'); // returns YourEnum::ENUM2
YourEnum::make(1); // returns YourEnum::ENUM2
YourEnum::make('your_enum'); // returns YourEnum::ENUM
YourEnum::make('your_other_enum'); // returns YourEnum::ENUM2
YourEnum::make('ENUM3'); // throws exception

/** tryMake */
YourEnum::tryMake('ENUM'); // returns YourEnum::ENUM
YourEnum::tryMake('0'); // returns YourEnum::ENUM
YourEnum::tryMake(1); // returns YourEnum::ENUM
YourEnum::tryMake('ENUM2'); // returns YourEnum::ENUM2
YourEnum::tryMake('your_enum'); // returns YourEnum::ENUM
YourEnum::tryMake('your_other_enum'); // returns YourEnum::ENUM2
YourEnum::tryMake('ENUM3'); // returns null
YourEnum::tryMake(3); // returns null

/** makeArray */

YourEnum::makeArray(
    ['ENUM', 'your_other_enum']
); // returns [YourEnum::ENUM, YourEnum::ENUM2]
YourEnum::makeArray(['ENUM', 'unknown']); // throws exception
/** tryMakeArray */

YourEnum::tryMakeArray(
    ['ENUM', 'your_other_enum']
); // returns [YourEnum::ENUM, YourEnum::ENUM2]
YourEnum::tryMakeArray(['ENUM', 'unknown']); // returns [YourEnum::ENUM]
```
