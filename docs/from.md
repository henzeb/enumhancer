# From

The methods `from` and `tryFrom` do not exist on non-backed enums. When you have
a non-backed enum where the string value is exactly the same as it's key, but
you need `from` or `tryFrom` because of some library (like Laravel's validation),
this might be useful.

## Usage

```php
use Henzeb\Enumhancer\Concerns\From;

enum yourEnum {
    use From;

    case MyEnum;
}
```

### Examples

```php
YourEnum::from('myenum'); // will return YourEnum::MyEnum;
YourEnum::from(0); // will return YourEnum::MyEnum;
YourEnum::from('MyEnum'); // will return YourEnum::MyEnum;
YourEnum::from('Callable'); // will throw error

YourEnum::from(YourEnum::MyEnum); // will return YourEnum::MyEnum;
YourEnum::from(YourOtherEnum::MyOtherEnum); // will throw error


YourEnum::tryFrom('myenum'); // will return YourEnum::MyEnum;
YourEnum::tryFrom('MyEnum'); // will return YourEnum::MyEnum;
YourEnum::tryFrom('DoesNotExist'); // will return null

YourEnum::tryFrom(YourEnum::MyEnum); // will return YourEnum::MyEnum;
YourEnum::tryFrom(YourOtherEnum::MyOtherEnum); // will return null;
```

Note: Under the hood it uses the Getters functionality, so you can use lower- and
uppercase names, and you can also use [Mappers](mappers.md) that you have
defined as described in [Defaults](mappers.md).

Warning: Be aware that this feature will not work if you have a backed enum.
Even when you use this trait, currently, the original methods take precedence.
For those situations use [Getters](getters.md)
