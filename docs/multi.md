# Multi

This allows you to do actions on a subset of the enums. Currently we only support `equals`, 
but in the future other features may be added.
## usage

```php
use Henzeb\Enumhancer\Concerns\Multi;

enum yourEnum {

    use Multi;
    
    case MY_ENUM;
    case MY_OTHER_ENUM;
    case MY_THIRD_ENUM;   
}
```

### Examples
#### equals
The `equals` method can come in handy when you need to compare one or more enums against a
subset of your enums.

Equals works just like the method in the [comparison](docs/comparison.md) trait.

```php
YourEnum::of(
    yourEnum::MY_ENUM, 
    yourEnum::MY_OTHER_ENUM
)->equals(YourEnum::MY_ENUM); // will return true

YourEnum::of(
    yourEnum::MY_ENUM, 
    yourEnum::MY_OTHER_ENUM
)->equals('MY_ENUM'); // will return true

YourEnum::of(
    yourEnum::MY_ENUM, 
    yourEnum::MY_OTHER_ENUM
)->equals('my_enum'); // will return true
    
YourEnum::of(
    yourEnum::MY_ENUM, 
    yourEnum::MY_OTHER_ENUM
)->equals(YourEnum::MY_ENUM, yourEnum::MY_THIRD_ENUM); // will return true

YourEnum::of(
    yourEnum::MY_ENUM, 
    yourEnum::MY_OTHER_ENUM
)->equals('MY_ENUM', 'my_other_enum'); // will return true

YourEnum::of(
    yourEnum::MY_ENUM, 
    yourEnum::MY_OTHER_ENUM
)->equals(YourEnum::MY_THIRD_ENUM); // will return false
```

