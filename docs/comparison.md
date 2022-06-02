# Comparison
This will allow you to easily compare enums, integers and strings with each other. 
This also is backwards-compatible with 
[Spatie's PHP Enum](https://github.com/spatie/enum)

## usage

```php
use Henzeb\Enumhancer\Concerns\Comparison;

enum YourEnum: string {
    use Comparison;
    
    CASE ENUM = 'your_value';
    CASE ENUM2 = 'your_other_value';
}

enum YourOtherEnum: int {
    use Comparison;
    
    CASE ENUM = 0;
    CASE ENUM2 = 1;
}

enum YourThirdEnum {
    use Comparison;
    
    CASE ENUM;
    CASE ENUM2;
}
```

### Examples
The method equals accepts multiple strings, integers or enums of the same type. 
If one of them matches, true will be returned.

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

YourOtherEnum::ENUM->equals(YourOtherEnum::ENUM); // returns true
YourOtherEnum::ENUM->equals(0); // returns true
YourOtherEnum::ENUM->equals(1); //returns false
YourOtherEnum::ENUM->equals(0, 1); //returns true

YourThirdEnum::ENUM->equals(YourThirdEnum::ENUM); // returns true
YourThirdEnum::ENUM->equals('ENUM'); // returns true
YourThirdEnum::ENUM->equals('ENUM2'); //returns false
YourThirdEnum::ENUM->equals('enum'); // returns true
YourThirdEnum::ENUM->equals('enum2'); //returns false
YourThirdEnum::ENUM->equals('enum', 'enum2'); //returns true
```

