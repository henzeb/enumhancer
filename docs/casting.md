# Eloquent Attribute Casting

Laravel supports casting backed enums out of the box, but what if you don't want
to use backed enums? This is where `CastsBasicEnumerations` comes in.

Note: for attribute casting with [State](state.md) see [here](#state).

## Usage

```php
enum yourEnum {
    case MY_ENUM;
}

use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Laravel\Concerns\CastsBasicEnumerations;

class YourModel extends Model
{
    use CastsBasicEnumerations;

    $casts = [
        'column' => YourEnum::class
    ];
}

```

### Lowercase values

By default, it will use the name of the basic enumeration as the value. If you
want the lowercase variant, you can add the `$keepEnumCase` property and set it
to false.

```php
class YourModel extends Model
{
    use CastsBasicEnumerations;

    private $keepEnumCase = false;

    $casts = [
        'column' => YourEnum::class
    ];
}

```

### State

When using [State](state.md), You want to prevent state changes that aren't
possible. `CastsStatefulEnumerations` does that. It will throw an
`IllegalEnumTransitionException` when a transition is not allowed

```php
enum elevator {
    use State;

    case Open;
    case Close;
    case Move;
    case Stop;
}

use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Laravel\Concerns\CastsStatefulEnumerations;

class YourModel extends Model
{
    use CastsStatefulEnumerations;

    $casts = [
        'state' => elevator::class
    ];
}

```

#### Examples

```php
$model = new YourModel();
$model->state = elevator::Open; // works
$model->state = elevator::Close; // works
$model->state = elevator::Stop; // IllegalEnumTransitionException
```

When you cast enums in your model that do not use `State`, but use the aggegrate
trait `Enhancers`, you can either use the individual needed traits on that enum,
or you can use the `$castsIgnoreEnumState` parameter on your Model.

```php
class YourModel extends Model
{
    // list of column name(s) you wish to ignore
    protected $castsIgnoreEnumState = ['maintenance_mode'];
}
```
