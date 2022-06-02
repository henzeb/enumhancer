# Labels

Just like [Spatie's PHP Enum](https://github.com/spatie/enum), you can add labels to 
your enums. This is largely backwards compatible with their package, except that it
also works for UnitEnum's in which case it returns the name if not specified.

## Usage

```php
use Henzeb\Enumhancer\Concerns\Labels;

enum YourEnum {
    use Labels;
    
    case ENUM;
    case NO_LABEL;
    
    public function labels(): array
    {
        return [
            'ENUM' => 'Your label';
        ];
    } 
}
```

### Examples
```php
YourEnum::ENUM->label(); // will return 'Your label'
YourEnum::NO_LABEL->label(); // will return 'NO_LABEL';
```
