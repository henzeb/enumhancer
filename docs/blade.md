# Blade

Currently, in blade enums aren't casted to strings. Due to limitations, you can't automate this by just adding 
UnitEnum/BackedEnum as stringables. Using this feature allows you easy registering of your enums for use in blade. 

## Example
```php
use Henzeb\Enumhancer\Concerns\Value;

enum MyUnitEnum {
    use Value;
    
    case Enum;
}

enum MyStringEnum: string {
    case Enum = 'My Enum';
}

enum MyIntEnum: int {
    case Enum = 0;
}
```

In your Service Provider:
```php
use Henzeb\Enumhancer\Helpers\EnumBlade;

EnumBlade::register(MyUnitEnum::class, MyStringEnum::class, MyIntEnum::class);

/** When you want the value to be lower case */
EnumBlade::registerLowercase(MyUnitEnum::class, MyStringEnum::class, MyIntEnum::class);
```

In your blade file:
```php
/** With register */
{{$unitEnum}} // Enum
{{$unitEnum->name}} // Enum
{{$unitEnum->value}} // throws error
{{$unitEnum->value()}} // enum
{{$unitEnum instanceof \UnitEnum}} // 1
{{$unitEnum instanceof \BackedEnum}} // 0

{{$stringEnum}} // My Enum
{{$stringEnum->name}} // Enum
{{$stringEnum->value}} // My Enum
{{$stringEnum instanceof \UnitEnum}} // 0
{{$stringEnum instanceof \BackedEnum}} // 1

{{$intEnum}} // 0
{{$intEnum->name}} // Enum
{{$intEnum->value}} // 0
{{$intEnum instanceof \UnitEnum}} // 0
{{$intEnum instanceof \BackedEnum}} // 1

/** With registerLowercase */
{{$unitEnum}} // enum
{{$unitEnum->name}} // Enum
{{$unitEnum->value}} // throws error
{{$unitEnum->value()}} // enum
{{$unitEnum instanceof \UnitEnum}} // 1
{{$unitEnum instanceof \BackedEnum}} // 0

{{$stringEnum}} // My Enum
{{$stringEnum->name}} // Enum
{{$stringEnum->value}} // My Enum
{{$stringEnum instanceof \UnitEnum}} // 0
{{$stringEnum instanceof \BackedEnum}} // 1

{{$intEnum}} // 0
{{$intEnum->name}} // Enum
{{$intEnum->value}} // 0
{{$intEnum instanceof \UnitEnum}} // 0
{{$intEnum instanceof \BackedEnum}} // 1
```

