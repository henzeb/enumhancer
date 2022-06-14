# Eloquent Attribute Casting

Laravel supports casting backed enums out of the box, but what if you don't want
to use backed enums? This is where `CastsBasicEnumerations` comes in.

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
