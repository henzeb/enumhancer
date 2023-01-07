# Changelog

All notable changes to `Enumhancer` will be documented in this file

## 1.22.0 - 2023-01-07

- added [asEnum](docs/formrequests.md) to laravel's FormRequests
- tiny fix in [isEnum](docs/laravel.validation.md#isEnum)
  validation: When [Defaults](docs/defaults.md) are used, it
  should fail validation.

## 1.21.0 - 2023-01-06

- added [(basic) enum binding](docs/binding.md) allowing you to bind
  basic enumerations to your routes and use Enumhancers secret sauce.
- Fixed a lot of potential issues with PHPstan.

## 1.20.0 - 2023-01-04

- bugfix in [Default](docs/defaults.md) where configured defaults would
  not override the by const defined value
- bugfix in [Mappers](docs/mappers.md) where mapping to integers was
  not allowed

### Extended features

- You can now set Mapper FQCN in constants starting with
 `map` and `map_flip`
- [Mappers](docs/mappers.md) methods now are usable statically
- All Laravel rules have now macro's set on `Rule`

### New features

- added [Bitmask](docs/bitmasks.md)
- added [Macros](docs/macros.md)
- added `isEnum` and `enumBitmask` rules

## 1.19.0 - 2022-12-15

- You can now use constants for [Mappers](docs/mappers.md)
 and [Defaults](docs/defaults.md)
- you can now flag a unit enum as `strict`, so you don't
 have to worry about casing in [Values](docs/value.md).

## 1.18.0 - 2022-12-14

- Added Magic method functionality to [State](docs/state.md)
- Added `to` and `tryTo` methods to `State`
- Added `is`, `isNot`, `isIn` and `isNotIn`
 to [Comparison](docs/comparison.md)

## 1.17.0 - 2022-12-13

- Added [Flip](docs/mappers.md#flip), allowing to use
 a single mapper for mapping between enums
- [From](docs/from.md)
 now allows `UnitEnum` objects for use with `Flip`
- [Comparison](docs/comparison.md) now allows different enums
 when used with [Mappers](docs/mappers.md)
- Deprecated [Makers](docs/makers.md), replaced by
 [Getters](docs/getters.md)

## 1.16.0 - 2022-12-11

- Added [Configure](docs/configure.md)
- Added [Dropdown](docs/dropdown.md)
- [Comparison](docs/configure.md) now accepts null values
- Fixed bug in [Casting](docs/casting.md) where in the latest Laravel versions
 the `Keep Enum Value Case` switch no longer worked.

## 1.15.0 - 2022-06-21

- Made the Laravel [Reporter](docs/reporters.md#laravel) configurable
- added `key` method to [Value](docs/value.md)

## 1.14.0 - 2022-06-19

- Added transition hooks [State](docs/state.md)
- [Makers](docs/makers.md) & [From](docs/from.md) now allow you to use integer
  keys on basic and string enums

## 1.12.0 - 2022-06-15

- Added casting support for [State](docs/state.md)

## 1.11.0 - 2022-06-14

- Added [State](docs/state.md) that allows you to have transitions with enums

## 1.10.0 - 2022-06-12

- Added [Defaults](docs/defaults.md) that allows you to have default enums

## 1.9.0 - 2022-06-08

- Added [Blade](docs/blade.md) support

## 1.8.0 - 2022-06-07

- Added [Helper functions](docs/functions.md) to ease usage of basic enums

## 1.7.0 - 2022-06-06

- When using [Comparison](docs/comparison.md), you can now assert with `is`
  or `isNot`

## 1.6.0 - 2022-06-04

- Added Eloquent Casting support for basic enumerations

## 1.5.0 - 2022-05-31

- Added [Extractor](docs/extractor.md) to extract enums from a string mentioned
  by value
- Some documentation repairs

## 1.4.1 - 2022-03-04

- Added `cases` method to `Subset`

## 1.4.0 - 2022-03-02

- Renamed Multi to Subset
- Added `names` method to `Subset`
- Added `values` method to `Subset`
- Added `do` method to `Subset`

## 1.3.0 - 2022-02-28

- Added Multi. Currently allows you to compare against a subset of your enum

## 1.2.0 - 2022-02-26

- Added Value (for use with basic enums)

## 1.1.0 - 2022-02-25

- Added From. Useful for situations where you need them with basic enums

## 1.0.2 - 2022-02-16

- Bugfix: Constructor did not use internal mapper

## 1.0.1 - 2022-02-16

- You can now define a mapper in a method
- When you use an empty string or null in mappable, it will return null now

## 1.0.0 - 2022-02-15

- Initial release
