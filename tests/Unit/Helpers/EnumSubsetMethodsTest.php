<?php

use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Helpers\Subset\EnumSubsetMethods;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownIntLabeledEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownStringLabeledEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Dropdown\DropdownLabeledUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Dropdown\DropdownUnitEnum;

test('should throw error with wrong enum type', function () {
    new EnumSubsetMethods(IntBackedEnum::class, EnhancedUnitEnum::ENUM);
})->throws(TypeError::class);

test('equals should return false when no enums passed', function () {
    expect(
        (new EnumSubsetMethods(IntBackedEnum::class))
            ->equals()
    )->toBeFalse();
});

test('equals should return false when null passed', function () {
    expect(
        (new EnumSubsetMethods(IntBackedEnum::class))
            ->equals(null)
    )->toBeFalse();
});

test('equals should return true', function () {
    expect(
        (new EnumSubsetMethods(IntBackedEnum::class, IntBackedEnum::TEST))
            ->equals(IntBackedEnum::TEST)
    )->toBeTrue();
});

test('equals multi should return true', function () {
    expect(
        (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
            ->equals(IntBackedEnum::TEST)
    )->toBeTrue();
});

test('equals multi with null should return true', function () {
    expect(
        (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
            ->equals(IntBackedEnum::TEST, null)
    )->toBeTrue();
});

test('names should return array of names', function () {
    expect(
        (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
            ->names()
    )->toBe(getNames(IntBackedEnum::cases()));
});

test('value should return array of values string backed', function () {
    expect(
        (new EnumSubsetMethods(StringBackedGetEnum::class, ...StringBackedGetEnum::cases()))
            ->values()
    )->toBe(getValues(StringBackedGetEnum::cases()));
});

test('value should return array of values int backed', function () {
    expect(
        (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
            ->values()
    )->toBe(getValues(IntBackedEnum::cases()));
});

test('value should return array of values unit enums', function () {
    expect(
        (new EnumSubsetMethods(EnhancedUnitEnum::class, ...EnhancedUnitEnum::cases()))
            ->values()
    )->toBe(getValues(EnhancedUnitEnum::cases()));
});

test('value should return array of values unit enums without value', function () {
    expect(
        (new EnumSubsetMethods(SubsetUnitEnum::class, ...SubsetUnitEnum::cases()))
            ->values()
    )->toBe(getValues(SubsetUnitEnum::cases()));
});

test('should run closure on array of enums', function () {
    $enums = [];
    (new EnumSubsetMethods(EnhancedUnitEnum::class, ...EnhancedUnitEnum::cases()))
        ->do(
            function (EnhancedUnitEnum $enum) use (&$enums) {
                $enums[] = $enum;
            }
        );
    expect($enums)->toBe(EnhancedUnitEnum::cases());
});

test('cases should return subset of cases', function (array $cases) {
    expect(
        (new EnumSubsetMethods(EnhancedUnitEnum::class, ...$cases))->cases()
    )->toBe($cases);
})->with([
    [[EnhancedUnitEnum::ENUM]],
    [[EnhancedUnitEnum::ENUM, EnhancedUnitEnum::THIRD_ENUM]]
]);

test('dropdown', function (string $enum, array $expected, bool $keepCase = false) {
    expect((new EnumSubsetMethods($enum, ...$enum::cases()))->dropdown($keepCase))->toBe($expected);
})->with([
    [
        DropdownUnitEnum::class,
        ['orange' => 'Orange', 'apple' => 'Apple', 'banana' => 'Banana']
    ],
    [
        DropdownUnitEnum::class,
        ['Orange' => 'Orange', 'Apple' => 'Apple', 'Banana' => 'Banana'],
        true
    ],
    [
        DropdownLabeledUnitEnum::class,
        ['orange' => 'an orange', 'apple' => 'an apple', 'banana' => 'a banana']
    ],
    [
        DropdownLabeledUnitEnum::class,
        ['Orange' => 'an orange', 'Apple' => 'an apple', 'Banana' => 'a banana'],
        true
    ],
    [
        DropdownIntEnum::class,
        [2 => 'Orange', 3 => 'Apple', 5 => 'Banana']
    ],
    [
        DropdownIntLabeledEnum::class,
        [2 => 'an orange', 3 => 'an apple', 5 => 'a banana']
    ],
    [
        DropdownStringEnum::class,
        ['My orange' => 'Orange', 'My apple' => 'Apple', 'My banana' => 'Banana']
    ],
    [
        DropdownStringLabeledEnum::class,
        ['My orange' => 'an orange', 'My apple' => 'an apple', 'My banana' => 'a banana']
    ],
    [
        DropdownStringLabeledEnum::class,
        ['My orange' => 'an orange', 'My apple' => 'an apple', 'My banana' => 'a banana'],
        true
    ]
]);

function getNames(array $cases): array
{
    return array_map(fn($enum) => $enum->name, $cases);
}

function getValues(array $cases): array
{
    return array_map(fn($enum) => EnumValue::value($enum), $cases);
}
