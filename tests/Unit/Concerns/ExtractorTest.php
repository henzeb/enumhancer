<?php

use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\ExtractBackedEnum;
test('should find enum in text', function () {
    expect(ExtractBackedEnum::extract('this is an enum test text'))->toBe([
        ExtractBackedEnum::AN_ENUM
    ]);
});

test('should find enum in text case insensitive', function () {
    expect(ExtractBackedEnum::extract('this is an ENUM test text'))->toBe([
        ExtractBackedEnum::AN_ENUM
    ]);
});

test('should not find enum in text', function () {
    expect(ExtractBackedEnum::extract('This text contains nothing'))->toBe([]);
});

test('should find multiple enum in text', function () {
    expect(ExtractBackedEnum::extract('this is an enum test and this is another enum text'))->toBe([
        ExtractBackedEnum::AN_ENUM,
        ExtractBackedEnum::ANOTHER_ENUM
    ]);
});

test('unique multiple enums in text', function () {
    expect(ExtractBackedEnum::extract('an enum An ENUM an enum'))->toBe([
        ExtractBackedEnum::AN_ENUM,
        ExtractBackedEnum::AN_ENUM,
        ExtractBackedEnum::AN_ENUM
    ]);
});

test('should not match parts of words', function () {
    expect(ExtractBackedEnum::extract('an enums An ENUM an enum'))->toBe([
        ExtractBackedEnum::AN_ENUM,
        ExtractBackedEnum::AN_ENUM
    ]);
});

test('extraction with unit enum', function () {
    expect(SubsetUnitEnum::extract('an enum An ENUM an EnUm'))->toBe([
        SubsetUnitEnum::ENUM,
        SubsetUnitEnum::ENUM,
        SubsetUnitEnum::ENUM,
    ]);
});
