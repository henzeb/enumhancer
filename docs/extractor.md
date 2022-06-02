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

enum YourOtherEnum: int {
    use Extractor;
    
    case ENUM = 0;
    case ENUM2 = 1;
}
```

### Examples
```php
YourEnum::extract('you can find another enum here'); // returns [YourEnum::ENUM2]
YourEnum::extract('A lot of text with (enum)'); // returns [YourEnum::ENUM]
YourEnum::extract('another enum (enum)'); // returns [YourEnum::ENUM2, YourEnum::ENUM]
YourEnum::extract('extract case sensitive another ENUM') // returns [YourEnum::ENUM2];
YourEnum::extract('contains multiple enums') // returns [];

YourOtherEnum::extract('I found 1 truth'); // returns [YourOtherEnum::ENUM2]
YourOtherEnum::extract('I found 1 truth and 0 lies'); // returns [YourOtherEnum::ENUM2, YourOtherEnum::ENUM]
YourOtherEnum::extract('I found 100 lies'); // returns []
```

Note: You can use `Mappers` in combination with `Extractor`. This might be helpful
when the source has multiple or different notations of your enum cases.
