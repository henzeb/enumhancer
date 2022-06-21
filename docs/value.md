# Value

Basic enums don't have a value. But if you want to know their numeric key or
their value, you are referred to name and `cases()` respectively to find out.
`Value` eases that problem for you.

## Usage

```php
use Henzeb\Enumhancer\Concerns\Value;

enum yourEnum {
    use Value;

    case MY_ENUM;
    case Other;

}
```

### Examples

```php
YourEnum::MY_ENUM->value(); // will return `my_enum`;
YourEnum::Other->value(); // will return `other`;

YourEnum::MY_ENUM->key(); // will return `0`;
YourEnum::Other->key(); // will return `1`;
```

Note: When used with a string or int backed enum, `value` will return it's
actual value.

Note: When used with an int backed enum, `key` will return the value.
