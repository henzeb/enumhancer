# Enumhancer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/henzeb/enumhancer.svg?style=flat-square)](https://packagist.org/packages/henzeb/enumhancer)
[![Total Downloads](https://img.shields.io/packagist/dt/henzeb/enumhancer.svg?style=flat-square)](https://packagist.org/packages/henzeb/enumhancer)

In this library you'll find some of the most common use-cases for enums. 
If you find yourself recreating functionalities, maybe this package is 
something for you.

This package currently supports the following:

- Constructor (in case you're migrating from
  [Spatie's PHP Enum](https://github.com/spatie/enum))
- Comparison
- From (for unit enums)
- Make (Ability to make from enum-name)
- Labels
- Mappers
- Multi
- Properties
- Reporting (Logging)
- Value


## Installation

You can install the package via composer:

```bash
composer require henzeb/enumhancer
```

## Usage
You can simply add the `Enhancers` trait to your `enum` in 
order to use almost all functionality of this package. All functionality 
should work with `unit` enums as well as `backed` enums'.

```php
use Henzeb\Enumhancer\Concerns\Enhancers\Enhancers;

enum YourEnum {
    use Enhancers;
    
    // ...
} 
```
You can also just use one of the functionalities by using the specific trait 
for that functionality. 

Note: all traits can be used next to each other, except for `Mappers`, which has 
implemented his own version of `Makers` and `Reporters`.

### Functionality
- [Constructor](docs/constructor.md)
- [Comparison](docs/comparison.md)
- [From](docs/from.md)
- [Labels](docs/labels.md)
- [Makers](docs/makers.md)
- [Mappers](docs/mappers.md)
- [Subset](docs/subset.md)
- [Properties](docs/properties.md)
- [Reporters](docs/reporters.md)
- [Value](docs/value.md)

### Laravel
When you are installing this package into a laravel project, Enumhancer will
automatically set the global `Reporter` for the `makeOrReport` methods, so that
it will use Laravel's `Log` facade.

If you don't want that to happen, you can tell Laravel not to 
discover the package.

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

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email henzeberkheij@gmail.com instead of using the issue tracker.

## Credits

- [Henze Berkheij](https://github.com/henzeb)

## License

The GNU AGPLv. Please see [License File](LICENSE.md) for more information.
