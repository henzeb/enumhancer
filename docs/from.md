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
defined using the `mappers` method and the [Defaults](mappers.md).

By default, from and tryFrom do not use [Mappers](mappers.md). But when
an UnitEnum object is passed, it will try to map the value first. If
no match is found, it will use the `name`.

Warning: Be aware that this feature will not work if you have a backed enum.
Even when you use this trait, currently, the original methods take precedence.
