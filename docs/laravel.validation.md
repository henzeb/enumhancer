# Laravel rules (validation)

Laravel supports enum validation out of the box. For other situations Enumhancer
can help you out.

## Basic enums

For validation of basic enums see [From](from.md).

## State

This rule is for use in conjunction with [State](state.md).

The state you start from can come from the database (when updating) or can 
be a default one (when creating new).

### example

```php
use Henzeb\Enumhancer\Laravel\Rules\EnumTransition;

# rules
[
    'state' => [new EnumTransition(elevator::Open)]
]

# input
['state','close'] // validates
['state','Close'] // validates
['state', 'Move'] // fails to validate

```

### message
Key: `validation.enumhancer.transition`

| Key   | description                    |
|-------|--------------------------------|
| :from | Enum the transition comes from |
| :to   | Enum the transition goes to    |
