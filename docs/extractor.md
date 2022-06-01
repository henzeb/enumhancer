# Extractor
Sometimes you have a piece of text that contains an enum value that you 
want to use. With this you can simply pass a multiline string and extract all 
enums that are mentioned by value. 

Note: This also works with `Mappers`, you can either pass along a mapper as second parameter or use the `Mappers`
trait in case you need the default mapper feature.

## Usage
```php
use Henzeb\Enumhancer\Concerns\Extractor;

enum YourEnum: string {
    use Extractor;
    
    case ENUM = 'enum';
    case ENUM2 = 'another enum';
}
```

### Examples
```php
YourEnum::extract('you can find another enum here'); // returns [YourEnum::ENUM2]
YourEnum::extract('A lot of text with (enum)'); // returns [YourEnum::ENUM]
YourEnum::extract('another enum (enum)'); // returns [YourEnum::ENUM2, YourEnum::ENUM]
YourEnum::extract('extact case sensitive another ENUM') // returns [YourEnum::ENUM2];
```

Note: You can use `Mappers`
