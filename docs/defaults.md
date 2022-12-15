# Defaults

[Mappers](mappers.md) already allow you to define a 'default' value. This
however require you to create a mapper object. If you don't need mappers, or
want a convenient `default` method, `Defaults` is your poison.

## Usage

```php
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\Makers;

enum YourEnum {
    use Defaults;

    case MyEnum;
}

enum YourDefaultEnum {
    use Defaults;

    case MyEnum;
    case Default;
}

enum MyDefaultConstEnum {
    use Defaults, Makers;

    case MyEnum;
    case MyDefaultEnum;

    const Default = MyDefaultEnum::MyDefaultEnum;
}

enum MyDefaultEnum {
    use Defaults, Makers;

    case MyEnum;
    case Default;
    case MyDefaultEnum;

    public static function default() : ?self
    {
        return self::MyDefaultEnum;
    }
}
```

### examples

```php
YourEnum::default(); //returns null
YourEnum::MyEnum->isDefault(); // returns false
YourEnum::MyEnum->isNotDefault(); // returns true
YourEnum::get('default'); // throws error
YourEnum::tryGet('default'); // returns null

YourDefaultEnum::default(); //returns YourDefaultEnum::Default
YourDefaultEnum::Default->isDefault(); // returns true
YourDefaultEnum::Default->isNotDefault(); // returns false
YourDefaultEnum::get('default'); // YourDefaultEnum::Default
YourDefaultEnum::get('unknown'); // crashes
YourDefaultEnum::tryGet('default'); // returns YourDefaultEnum::Default
YourDefaultEnum::tryGet('unknown'); // returns YourDefaultEnum::Default

MyDefaultConstEnum::default(); //returns MyDefaultEnum::MyDefaultEnum
MyDefaultConstEnum::MyDefaultEnum->isDefault(); // returns true
MyDefaultConstEnum::MyDefaultEnum->isNotDefault(); // returns false
MyDefaultConstEnum::get('default'); // MyDefaultEnum::MyDefaultEnum
MyDefaultConstEnum::get('unknown'); // crashes
MyDefaultConstEnum::tryGet('default'); // returns MyDefaultEnum::MyDefaultEnum
MyDefaultConstEnum::tryGet('unknown'); // returns MyDefaultEnum::MyDefaultEnum

MyDefaultEnum::default(); //returns MyDefaultEnum::MyDefaultEnum
MyDefaultEnum::MyDefaultEnum->isDefault(); // returns true
MyDefaultEnum::MyDefaultEnum->isNotDefault(); // returns false
MyDefaultEnum::get('default'); // MyDefaultEnum::MyDefaultEnum
MyDefaultEnum::get('unknown'); // crashes
MyDefaultEnum::tryGet('default'); // returns MyDefaultEnum::MyDefaultEnum
MyDefaultEnum::tryGet('unknown'); // returns MyDefaultEnum::MyDefaultEnum

```

Note: [From](from.md) will use the default value as well, but only when used
with basic enums.

Note: You can also map default using [Mappers](mappers.md). The Defaults methods
will then use the mapped value.
