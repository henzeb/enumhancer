# Comparison
This will allow you to easily compare enums and strings with each other. This 
also is backwards-compatible with [Spatie's PHP Enum](https://github.com/spatie/enum)

## usage

```php
use Henzeb\Enumhancer\Concerns\Comparison;

enum YourEnum: string {
    use Comparison;
    
    CASE ENUM = 'your_value';
    CASE ENUM2 = 'your_other_value';
}
```

### Examples
The method equals accepts multiple strings or enums. If one of
them matches, true will be returned.

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
```
