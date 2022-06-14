# Reporters

Sometimes you don't want your code to fail, yet you want to know when an enum is
tried to be instantiated, but failed. This allows you to do so.

## usage

```php
use Henzeb\Enumhancer\Concerns\Reporters;


enum YourEnum: string {
    use Reporters;

    case ENUM = 'your_enum';
    case ENUM2 = 'your_other_enum';
}
use Henzeb\Enumhancer\Contracts\Reporter;

class YourReporter implements Reporter {

    public function report(
    string $enum,
    ?string $key,
    ?BackedEnum $context
    ) :void {
        // TODO: Implement report() method.
    }
}

```

### Examples

```php
/** in the root of your project (or ServiceProvider in case of Laravel) */
Henzeb\Enumhancer\Helpers\EnumReporter::set(new YourReporter());
Henzeb\Enumhancer\Helpers\EnumReporter::set(YourReporter::class);

/** makeOrReport */
YourEnum::makeOrReport('ENUM'); // will just return the enum
YourEnum::makeOrReport('your_enum'); // will just return the enum
YourEnum::makeOrReport('unknown'); // will return null and call the Reporter

YourEnum::makeOrReportArray(
    ['ENUM', 'your_other_enum']
); // will just return the enums

YourEnum::makeOrReportArray(
    ['ENUM', 'unknown']
); // will return [YourEnum::ENUM] and call the Reporter for 'unknown'
```

Note: each method accepts a `BackedEnum` so you can give a bit more context when
logging.

## Reporters per enum

You can also set a reporter for an enum by overriding the `reporter` method.
Enumhancer will use that one instead

```php
use Henzeb\Enumhancer\Contracts\Reporter;

enum YourEnum: string {
    use Reporters;

    case ENUM = 'your_enum';
    case ENUM2 = 'your_other_enum';

    public function reporter(): Reporter
    {
        return YourCustomReporter();
    }
}
```

## Laravel

For laravel there is out of the box support, see [README](../README.md#Laravel)
