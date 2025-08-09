# Laravel Eloquent



## Bitmask Query Scopes
The `InteractsWithBitmask` trait adds **expressive, reusable query scopes** to your Eloquent models for working with **bitmask columns**.
It simplifies filtering records based on bitwise values without manually writing bitwise SQL conditions.

### Features
- **whereBitmask** – Adds a `WHERE` condition to match records where the given bitmask **contains all bits** from the provided value. 
- **orWhereBitmask** – Adds an `OR WHERE` condition for the same logic. 
- Works with both **integer values** and **Bitmask enum instances**.


### Configuration
Apply the `InteractsWithBitmask` trait to your model, and set up the cast for your bitmask column.
```php
use Henzeb\Enumhancer\Laravel\Casts\AsBitmask;
use Henzeb\Enumhancer\Laravel\Traits\InteractsWithBitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmaskPreferenceEnum;
use Illuminate\Database\Eloquent\Model;


class MyModel extends Model
{
    use InteractsWithBitmask;
    
    
    protected $casts = [
        'preferences' => AsBitmask::class . ':' . BitmaskPreferenceEnum::class,
    ];
}
```

### Usage Examples

Using an Integer Value
```php
# match where 'preferences' has all bits in 5 set
MyModel::whereBitmask('preferences', 5)->get();

# same but using or condition
MyModel::orWhereBitmask('preferences', 5)->get();
```

Using a Bitmask Enum Instance
```php
$value = BitmaskPreferenceEnum::mask(
    BitmaskPreferenceEnum::AutoUpdates,
    BitmaskPreferenceEnum::DarkMode,
);

# match records where both flags are set
MyModel::whereBitmask('preferences', $value)->get();

# or condition
MyModel::orWhereBitmask('preferences', $value)->get();
```


> [!NOTE]
> If the value is `0`, the query matches **only** records where the column is exactly `zero`, ensuring no bits are set.
