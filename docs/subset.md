# Subset

This allows you to do certain actions on a subset of the enums.

## usage

```php
use Henzeb\Enumhancer\Concerns\Subset;

enum yourEnum {

    use Subset;

    case MY_ENUM;
    case MY_OTHER_ENUM;
    case MY_THIRD_ENUM;
}
```

### Examples

### of

to get a subset of cases you can use `of`.

```php
YourEnum::of(
    yourEnum::MY_ENUM,
    yourEnum::MY_OTHER_ENUM
)->cases(); // will return [yourEnum::MY_ENUM, yourEnum::MY_OTHER_ENUM]
```

### without

If you just want to single out one or more cases, you can use `without`.

```php
YourEnum::without(
    yourEnum::MY_ENUM,
    yourEnum::MY_OTHER_ENUM
)->cases(); // will return [yourEnum::MY_THIRD_ENUM]
```

Note: Each method used on `of` can be used on `without`

#### equals

The `equals` method can come in handy when you need to compare one or more enums
against a subset of your enums.

Equals works just like the method in the [comparison](docs/comparison.md) trait.

```php
YourEnum::of(
    yourEnum::MY_ENUM,
    yourEnum::MY_OTHER_ENUM
)->equals(YourEnum::MY_ENUM); // will return true

YourEnum::without(
    yourEnum::MY_ENUM,
    yourEnum::MY_OTHER_ENUM
)->equals(YourEnum::MY_ENUM); // will return false

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

### names

This method returns an array of names of the specified subset.

```php
YourEnum::of(
    yourEnum::MY_ENUM,
    yourEnum::MY_OTHER_ENUM
)->names(); // will return ['MY_ENUM', 'MY_OTHER_ENUM']
```

### values

This method returns an array of values of the specified subset.

```php
YourEnum::of(
    yourEnum::MY_ENUM,
    yourEnum::MY_OTHER_ENUM
)->values(); // will return ['my_enum', 'my_other_enum']
```

### do

This method allows you call a closure on each item in the subset.

```php
YourEnum::of(
    yourEnum::MY_ENUM,
    yourEnum::MY_OTHER_ENUM
)->do(
    function(yourEnum $enum) {
        print $enum->name.',';
    }); // will print MY_ENUM,MY_OTHER_ENUM
```
