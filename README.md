# Enumhancer

[![Build Status](https://github.com/henzeb/enumhancer/workflows/tests/badge.svg)](https://github.com/henzeb/enumhancer/actions)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5cee34b5dd839b0c2cdd/test_coverage)](https://codeclimate.com/github/henzeb/enumhancer/test_coverage)
[![Total Downloads](https://img.shields.io/packagist/dt/henzeb/enumhancer.svg)](https://packagist.org/packages/henzeb/enumhancer)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/henzeb/enumhancer.svg)](https://packagist.org/packages/henzeb/enumhancer)
[![License](https://img.shields.io/packagist/l/henzeb/enumhancer)](https://packagist.org/packages/henzeb/enumhancer)

This package is your framework-agnostic Swiss Army knife when it comes to
PHP 8.1's native enums. In this package you will find a lot of tools for
the most common use cases, and more will be added in the future.

If you have an idea, or you miss something that needs to be added, just let me
know.

Enumhancer is case-agnostic, which means `Enum` equals `ENUM` equals `enum`.
This is done with the idea that it is useless to have two different enums
having the same name and different casing.

It is also type-agnostic. This way you can steer clear as much as possible
from the extra work that comes with backed enums.

Note: While most functionality that also exists in Spatie's PHP Enum is made
backwards compatible to allow for an easy migration to PHP native enums,
currently this is not the case for their laravel package, PHPUnit assertions or
Faker Provider.

## Installation

You can install the package via composer:

```bash
composer require henzeb/enumhancer
```

## Usage

You can simply add the `Enhancers` trait to your `enum` in order to use almost
all functionality of this package. All features should work with `basic` enums as
well as `backed` enums' unless stated otherwise.

```php
use Henzeb\Enumhancer\Concerns\Enhancers;

enum YourEnum {
    use Enhancers;

    // ...
}
```

You can also just use one of the features by using the specific trait for that
feature.

Note: all traits can be used next to each other, except for `Mappers`, which has
implemented the methods of `Getters`, `Extractor` and `Reporters`.

### Features

- [Constructor](docs/constructor.md)
- [Comparison](docs/comparison.md)
- [Configure](docs/configure.md)
- [Defaults](docs/defaults.md)
- [Dropdown](docs/dropdown.md)
- [Extractor](docs/extractor.md)
- [From](docs/from.md)
- [Getters](docs/getters.md)
- [Labels](docs/labels.md)
- ~~[Makers](docs/makers.md)~~ (deprecated)
- [Mappers](docs/mappers.md)
- [Properties](docs/properties.md)
- [Reporters](docs/reporters.md)
- [State](docs/state.md)
- [Subset](docs/subset.md)
- [Value](docs/value.md)

### Helper functions

- [Backing](docs/functions.md#backing)
- [Name](docs/functions.md#name)
- [Value](docs/functions.md#value)

### Laravel specific Features

- [Blade](docs/blade.md)
- [Casting](docs/casting.md)
- [Validation](docs/laravel.validation.md)

### Laravel's auto-discovery

When you are installing this package into a laravel project, Enumhancer will
automatically set the global `Reporter` for the `makeOrReport` methods, so that
it will use Laravel's `Log` facade.

If you don't want that to happen, you can tell Laravel not to discover the
package.

```composer
"extra": {
        "laravel": {
            "dont-discover": [
                "henzeb/enumhancer"
            ]
        }
    }
```

### Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed
recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email
henzeberkheij@gmail.com instead of using the issue tracker.

## Credits

- [Henze Berkheij](https://github.com/henzeb)

## License

The GNU AGPLv. Please see [License File](LICENSE.md) for more information.
