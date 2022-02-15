# Properties

Enums do not support properties. This is due to the simple fact 
that enums are forbidden to have any form of state. But sometimes you need to 
store something.

Note: Just to follow the rules of PHP, the properties are stored per 
`enum object` or globally for all enums and the methods are 
therefore `static`. 

## Usage

```php
use Henzeb\Enumhancer\Concerns\Properties;

enum YourEnum: string {
    use Properties;
    
    // ..
}
```

### Examples
```php
YourEnum::property('your_property'); // will return null;
YourEnum::property('your_property', 'your_value');
YourEnum::property('your_property'); // will return 'your_value'
YourEnum::property('your_property', 100);
YourEnum::property('your_property'); // will return 100
YourEnum::property('your_property', null);
YourEnum::property('your_property'); // will return null
YourEnum::property('your_property', fn()=>'true');
YourEnum::property('your_property'); // will return callable
YourEnum::property('your_property', new stdClass());
YourEnum::property('your_property'); // will return stdClass() instance

YourEnum::unset('your_property'); // will remove you_property
YourEnum::unsetAll(); // will clear all properties.
```

## Global properties
You can also set global properties.
```php
Henzeb\Enumhancer\Helpers\EnumProperties::global('property', 'your_value');
Henzeb\Enumhancer\Helpers\EnumProperties::clearGlobal(); // clear all global properties
```
