# Laravel rules (validation)

Laravel supports enum validation out of the box. For other situations Enumhancer
can help you out.

## Validating enums

For validation of unit enums see [From](from.md). This will work for most
cases.

Alternatively, you can use `isEnum` which will also allow you to inject
[Mappers](mappers.md) as second parameter.

Note: Unlike Laravel's own rule, when using backed enums with a default,
`isEnum` will fail the validation.

```php
use Henzeb\Enumhancer\Laravel\Rules\IsEnum;

enum Permission {
    case Execute;
    case Read;
    case Write;
}

# rules
[
    'permission' => [Rule::isEnum(Permission::class)]
]

[
    'permissions' => 'array',
    'permissions.*' => [new IsEnum(Permission::class)]
]

# Input
['permission'=> 0 ] // returns true
['permission'=> 9 ] // returns false
['permission'=> 'Read' ] // returns true
['permission'=> 'write' ] // returns true
['permission'=> 'Read' ] // returns true
['permission'=> 'Update' ] // returns false

['permissions'=> [ 0 ] ] // returns true
['permissions'=> [ 0, 9 ] // returns false
['permissions'=> ['Read'] ] // returns true
['permissions'=> ['write'] ] // returns true
['permissions'=> ['Read'] ] // returns true
['permissions'=> ['Update'] ] // returns false
['permissions'=> [ 'Read','Update'] ] // returns false
````

### message for isEnum

Key: `validation.enumhancer.enum`

| Key    | description      |
|--------|------------------|
| :enum  | Enum type        |
| :value | The given value  |

## Bitmask

This rule is used with [Bitmask](bitmasks.md).

This rule validates the given value for multiple bits by default, but allows
you to validate for single bits.

```php
use Henzeb\Enumhancer\Laravel\Rules\EnumBitmask;

enum Permission {
    use Henzeb\Enumhancer\Concerns\Bitmasks;

    case Execute; // 1
    case Read; // 2
    case Write; // 4
}

# rules
[
    'permission' => [Rule::enumBitmask(Permission::class)]
]

[
    'single' => [new EnumBitmask(Permission::class, true)] // single bits
]

# input
['permission' => 7] // returns true
['permission' => 1] // returns true
['permission' => 0] // returns true
['permission' => '2'] // returns true
['permission' => -1] // returns false
['permission' => 'a-string'] // returns false

['single' => 7] // returns false
['single' => 1] // returns true
['single' => 0] // returns true
['single' => '2'] // returns true
['single' => -1] // returns false
['single' => 'a-string'] // returns false
```

### message for enumBitmask

Key: `validation.enumhancer.bitmask`

| Key    | description      |
|--------|------------------|
| :enum  | Enum type        |
| :value | The given value  |

## EnumTransition

This rule is used with [State](state.md).

The state you start from can come from the database (when updating) or can be a
default one (when creating new).

It's also possible to pass a `TransitionHooks` as second parameter.

```php
use Henzeb\Enumhancer\Laravel\Rules\EnumTransition;

# rules
[
    'state' => [Rule::enumTransition(elevator::Open)]
]
[
    'state' => [new EnumTransition(elevator::Open, new YourTransitionHooks())]
]

# input
['state' => 'close'] // validates
['state' => 'Close'] // validates
['state' => 'Move'] // fails to validate

```

### message

Key: `validation.enumhancer.transition`

| Key   | description                    |
|-------|--------------------------------|
| :from | Enum the transition comes from |
| :to   | Enum the transition goes to    |
