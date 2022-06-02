# Value

When you have a UnitEnum you have no value. You have to use the `name` value. But what if you
want to have the lowercase version of that name? That's where `Value` comes in.

## Usage

```php
use Henzeb\Enumhancer\Concerns\Value;

enum yourEnum {
    use Value;
    
    case MY_ENUM;
    
}
```

### Examples
```php
YourEnum::MY_ENUM->value(); // will return `my_enum`;
```

Note: When used with a string or int backed enum, this method will return it's actual value.
