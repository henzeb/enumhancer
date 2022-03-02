# Changelog

All notable changes to `Enumhancer` will be documented in this file

## 1.4.0 - 2022-03-02

- Renamed Multi to Subset
- Added `names` method to `Subset`
- Added `values` method to `Subset`
- Added `do` method to `Subset`

## 1.3.0 - 2022-02-28

- added Multi. Currently allows you to compare against a subset of your enum.

## 1.2.0 - 2022-02-26

- added Value (for use with unit enums)

## 1.1.0 - 2022-02-25

- added From. Useful for situations where you need them with non-backed enums

## 1.0.2 - 2022-02-16

- Bugfix: Constructor did not use internal mapper

## 1.0.1 - 2022-02-16

- You can now define a mapper in a method
- When you use an empty string or null in mappable, it will return null now

## 1.0.0 - 2022-02-15

- initial release
