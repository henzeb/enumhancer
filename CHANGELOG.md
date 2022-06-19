# Changelog

All notable changes to `Enumhancer` will be documented in this file

## 1.12.0 - 2022-06-19

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
