# Mappers

This will allow you to map strings to existing enums. This is useful when you 
for example are building against multiple third party API's and you 
need to translate the enums between one and other.

Note: `Mappers` is the only one that you cannot use together with 
`Makers` and `Reporters`. This is simply due to the fact that it is 
implementing the methods for those by itself. Under the hood it's using 
their functionality, so the methods will work just the same.

## Usage

```php
use Henzeb\Enumhancer\Concerns\Mappers;


enum YourEnum {
    use Mappers;
    
    case ENUM;
    case NO_LABEL;
    case NOT_MAPPED;
 
}
use Henzeb\Enumhancer\Contracts\Mapper;

class YourMapper extends Mapper {

    public function mappable() : array
    {
         return [
            'Mapped' => YourEnum::ENUM,
            'LABEL_MISSING' => 'NO_LABEL'
         ];       
    }
}
```

### Examples
You can either use your instantiated `YourMapper` or just 
the FQCN `YourMapper::class`.

```php
/** make */
YourEnum::make('Mapped', YourMapper::class); // will return YourEnum::ENUM
YourEnum::make('NOT_MAPPED', new YourMapper()); // will return YourEnum::NOT_MAPPED
YourEnum::make('unknown', YourMapper::class); // will throw exception

/** tryMake */
YourEnum::tryMake('Mapped', YourMapper::class); // will return YourEnum::ENUM
YourEnum::tryMake('NOT_MAPPED', new YourMapper()); // will return YourEnum::NOT_MAPPED
YourEnum::tryMake('unknown', YourMapper::class); // will return null
/** makeArray */
YourEnum::makeArray(['Mapped', 'NOT_MAPPED'], YourMapper::class); // will return [YourEnum::ENUM, YourEnum::NOT_MAPPED]
YourEnum::makeArray(['unknown', 'NOT_MAPPED'], new YourMapper()); // will throw exception

/** tryMakeArray */
YourEnum::tryMakeArray(['Mapped', 'NOT_MAPPED'], YourMapper::class); // will return [YourEnum::ENUM, YourEnum::NOT_MAPPED]
YourEnum::tryMakeArray(['unknown', 'NOT_MAPPED'], new YourMapper()); // will return [YourEnum::NOT_MAPPED]

/** tryMakeArray */
YourEnum::makeOrReport(['Mapped', 'NOT_MAPPED'], YourMapper::class); // will return [YourEnum::ENUM, YourEnum::NOT_MAPPED]
YourEnum::makeOrReport(['unknown', 'NOT_MAPPED'], new YourMapper()); // will return [YourEnum::NOT_MAPPED]
```
Note: See for the `makeOrReport` method: [Reporters](reporters.md)

### Shared Mapper
You can also use one `Mapper` for multiple enums. Just use the FQCN of the enum
as a key in your array, like below:

```php
use Henzeb\Enumhancer\Contracts\Mapper;

class YourMapper extends Mapper {

    public function mappable() : array
    {
         return [
            YourEnum::class => [
                'Mapped' => YourEnum::ENUM,
                'LABEL_MISSING' => 'NO_LABEL'
            ] 
         ];       
    }
}
```
And then use the commands as shown in the `example` section.

Note: You can also mix them up, if some enums may share a value or a name.
