# Getters

Enums have out of the box the methods `from` and `tryFrom`. One drawback is that
you cannot use them to create `enum objects` with the name of the `enum`.

This allows you to do so.

## Usage

```php
use Henzeb\Enumhancer\Concerns\Getters;

enum YourEnum: string {
    use Getters;

    case ENUM = 'your_enum';
    case ENUM2 = 'your_other_enum';

    const ConstantEnum = self::ENUM;
}
```

### Examples

```php
/** get */
YourEnum::get('ENUM'); // returns YourEnum::ENUM
YourEnum::get('ConstantEnum'); // returns YourEnum::ENUM
YourEnum::get('0'); // returns YourEnum::ENUM
YourEnum::get(0); // returns YourEnum::ENUM
YourEnum::get('ENUM2'); // returns YourEnum::ENUM2
YourEnum::get('1'); // returns YourEnum::ENUM2
YourEnum::get(1); // returns YourEnum::ENUM2
YourEnum::get('your_enum'); // returns YourEnum::ENUM
YourEnum::get('your_other_enum'); // returns YourEnum::ENUM2
YourEnum::get('ENUM3'); // throws exception

/** tryGet */
YourEnum::tryGet('ENUM'); // returns YourEnum::ENUM
YourEnum::tryGet('ConstantEnum'); // returns YourEnum::ENUM
YourEnum::tryGet('0'); // returns YourEnum::ENUM
YourEnum::tryGet(1); // returns YourEnum::ENUM
YourEnum::tryGet('ENUM2'); // returns YourEnum::ENUM2
YourEnum::tryGet('your_enum'); // returns YourEnum::ENUM
YourEnum::tryGet('your_other_enum'); // returns YourEnum::ENUM2
YourEnum::tryGet('ENUM3'); // returns null
YourEnum::tryGet(3); // returns null

/** getArray */

YourEnum::getArray(
    ['ENUM', 'your_other_enum']
); // returns [YourEnum::ENUM, YourEnum::ENUM2]
YourEnum::getArray(['ENUM', 'unknown']); // throws exception
/** tryGetArray */

YourEnum::tryArray(
    ['ENUM', 'your_other_enum']
); // returns [YourEnum::ENUM, YourEnum::ENUM2]
YourEnum::tryArray(['ENUM', 'unknown']); // returns [YourEnum::ENUM]
```
