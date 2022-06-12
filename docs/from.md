# From

The methods `from` and `tryFrom` do not exist on non-backed enums. When you 
have a non-backed enum where the string value is exactly the same as it's key,
but you need `from` or `tryFrom` because of some library (like Laravel's 
validation), this might be useful.

## Usage

```php
use Henzeb\Enumhancer\Concerns\From;

enum yourEnum {
    use From;
    
    case MY_ENUM;
    
}
```

### Examples

```php
YourEnum::from('my_enum'); // will return YourEnum::MY_ENUM;
YourEnum::from('MY_ENUM'); // will return YourEnum::MY_ENUM;
YourEnum::from('CALLABLE'); // will throw error

YourEnum::tryFrom('my_enum'); // will return YourEnum::MY_ENUM;
YourEnum::tryFrom('MY_ENUM'); // will return YourEnum::MY_ENUM;
YourEnum::tryFrom('DOESNOTEXIST'); // will return null
```
Note: Under the hood it uses the Makers functionality, so you can use lower-
and uppercase names.

Note: This trait is not enabled by default when
using the `Enhancers` trait.
