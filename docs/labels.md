# Labels

Just like [Spatie's PHP Enum](https://github.com/spatie/enum), you can add labels to your enums. This is backwards
compatible with their package.

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
YourEnum::NO_LABEL->label(); // will return null;
```
