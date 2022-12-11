# Configure

Configure is very useful when you want to add enhanced enums to your own
package or shared library and allow users to modify behavior of certain
aspects.

For example: the `LogLevel` enum in this package is utilizing this trait,
so you can use this enum for your own purposes.

Note: this feature is not added by default to the `Enhancers` aggregate trait

## How to use

You can simply add the aggregate trait `Configure` to your enum to utilize all features.

```php
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Configure;
enum YourConfigurableEnum {
    use Enhancers, Configure;

    // ...
}
```

If you want to use a selection, you can choose between the following:

- [ConfigureDefaults](#configuredefaults)
- [ConfigureLabels](#configurelabels)
- [ConfigureMapper](#configuremapper)
- [ConfigureState](#configurestate)

### Example

```php
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\ConfigureDefaults;
enum YourConfigurableEnum {
    use Defaults, ConfigureDefaults;

    // ...
}
```

Each configurable option also has a `Once` method, which can be used
in a `Service Provider` or in the root of your project so you can
prevent changes later on if needed.

### ConfigureDefaults

Used with [Defaults](defaults.md)

#### ConfigureDefaults example

```php
use Henzeb\Enumhancer\Concerns\ConfigureDefaults;
use Henzeb\Enumhancer\Concerns\Enhancers;

enum YourEnum {
    use Enhancers, ConfigureDefaults;

    case AnEnum;
    // ...
}

yourEnum::setDefault(YourEnum::AnEnum);
yourEnum::setDefaultOnce(YourEnum::AnEnum);
```

### ConfigureLabels

Used with [Labels](labels.md)

#### ConfigureLabels example

```php
use Henzeb\Enumhancer\Concerns\ConfigureLabels;
use Henzeb\Enumhancer\Concerns\Enhancers;

enum YourEnum {
    use Enhancers, ConfigureLabels;

    case AnEnum;
    // ...
}

yourEnum::setLabels([YourEnum::AnEnum->name => 'An Enum']);
yourEnum::setLabelsOnce([YourEnum::AnEnum->name => 'An Enum']);
```

### ConfigureMapper

Used with [Mappers](mappers.md)

#### ConfigureMapper example

```php
use Henzeb\Enumhancer\Concerns\ConfigureMapper;

use Henzeb\Enumhancer\Concerns\Enhancers;

enum YourEnum {
    use Enhancers, ConfigureMapper;

    // ...
}

yourEnum::setMapper(new YourMapper());
yourEnum::setMapperOnce(new YourMapper());
```

### ConfigureState

Used with [State](state.md)

#### ConfigureState example

```php
use Henzeb\Enumhancer\Concerns\ConfigureState;

use Henzeb\Enumhancer\Concerns\Enhancers;

enum YourEnum {
    use Enhancers, ConfigureState;

    case Up;
    case Down;
    case Stop;

    // ...
}

yourEnum::setTransitionHook(new YourTransitionHook());
yourEnum::setTransitionHookOnce(new YourTransitionHook());
yourEnum::setTransitions(['Up'=>'Stop', 'Stop'=> ['Up', 'Down']]);
yourEnum::setTransitionHookOnce(['Up'=>'Stop', 'Stop'=> ['Up', 'Down']]);
```
