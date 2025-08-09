# Eloquent Attribute Casting

Laravel supports casting backed enums out of the box, but what if you don't want
to use backed enums? This is where `CastsBasicEnumerations` and `AsBitmask` comes in.

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

    /** Use this when you want to use hooks. */
    public function getTransactionHooks(string $attribute) : ?TransitionHooks{
        return match($attribute) {
            'state' => new YourElevatorHook($this->currentFloor);
        }
    }
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



### Bitmask
When you need to store multiple enum values in a single database column, you can use [Bitmasks](bitmasks.md) together with the `AsBitmask` cast. This allows you to efficiently store and retrieve sets of enum values as a single integer.

##### Enum:
First, define your enum and use the `Bitmasks` trait. Each case should have a unique power-of-two value.

```php
namespace App\Enums;

use Henzeb\Enumhancer\Concerns\Bitmasks;

enum Preferences: string
{
    use Bitmasks;

    private const BIT_VALUES = true;

    case LogActivity      = 1;
    case PushNotification = 2;
    case TwoFactorAuth    = 4;
    case DarkMode         = 8;
}
```

##### Model:
In your Eloquent model, use the AsBitmask cast for the relevant attribute. This will handle conversion between the integer in the database and your enum values.

```php
namespace App\Models;

use Henzeb\Enumhancer\Laravel\Casts\AsBitmask;
use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Laravel\Concerns\CastsStatefulEnumerations;
use App\Enums\Preferences;

class YourModel extends Model
{
    protected function casts(): array
    {
        return [
            'preferences' => AsBitmask::class . ':' . Preferences::class,
        ];
    }
}
```

#### Usage Examples

##### Setting Values
You can assign enum values to the attribute in several ways:
```php
$model = new YourModel;

// using the mask helper (stores 5: LogActivity + TwoFactorAuth)
$model->preferences = Preferences::mask(
    Preferences::LogActivity,
    Preferences::TwoFactorAuth,
);

// using an array (also stores 5)
$model->preferences = [
    Preferences::LogActivity,
    Preferences::TwoFactorAuth,
];

// single value (stores 1)
$model->preferences = Preferences::LogActivity;

// using a comma-separated string (stores 11: DarkMode + LogActivity + PushNotification)
$model->preferences = 'DarkMode,LogActivity,PushNotification';

// no preferences (stores 0)
$model->preferences = [];
$model->preferences = '';
$model->preferences = Preferences::mask();
```

##### Retrieving Values
When you retrieve the model, the `preferences` attribute will be an instance of Bitmask:
```php
$model = YourModel::first();
$model->preferences; // Bitmask instance with set values

// check if a specific preference is set
$model->preferences->has(Preferences::LogActivity); // true or false

// cet the raw integer value
$model->preferences->value(); // e.g. 5 for LogActivity and TwoFactorAuth
```

Tip: Using bitmasks is a space-efficient way to store multiple enum values in a single column, and the AsBitmask cast makes working with them in Eloquent models seamless.
