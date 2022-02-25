# Constructor

For people coming from [Spatie's PHP Enum](https://github.com/spatie/enum), 
this one will allow you to easily migrate away to actual enums.

## Usage
Add the `Constructor` trait and add the docblock just like you would with 
Spatie's package.

```php
use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self CALLABLE()
 */
enum yourEnum {
    use Constructor;
    
    case CALLABLE;
    
}
```

### Examples

```php
YourEnum::CALLABLE(); // will return YourEnum::CALLABLE;
```

Note: Under the hood it is using `__callStatic`, so it may give some 
unpredicted behavior when calling a method that doesn't exist.

Note: This trait is not enabled by default when 
using the `Enumhancer` trait.
