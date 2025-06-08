<?php

use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsMappedIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsMappedStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsNullIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsNullStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsOverriddenIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsOverriddenStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults\DefaultsStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsConstantEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsMappedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsNullEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsOverriddenEnum;

test('should return default', function (string $enum, mixed $expected) {
    expect($enum::default())->toBe($expected);
})->with([
    [DefaultsEnum::class, DefaultsEnum::Default],
    [DefaultsIntEnum::class, DefaultsIntEnum::Default],
    [DefaultsStringEnum::class, DefaultsStringEnum::Default],
    [DefaultsConstantEnum::class, DefaultsConstantEnum::DefaultEnum],
    [DefaultsNullEnum::class, null],
    [DefaultsNullIntEnum::class, null],
    [DefaultsNullStringEnum::class, null],
    [DefaultsOverriddenEnum::class, DefaultsOverriddenEnum::Enum],
    [DefaultsOverriddenIntEnum::class, DefaultsOverriddenIntEnum::Enum],
    [DefaultsOverriddenStringEnum::class, DefaultsOverriddenStringEnum::Enum],
    [DefaultsMappedEnum::class, DefaultsMappedEnum::Enum],
    [DefaultsMappedIntEnum::class, DefaultsMappedIntEnum::Enum],
    [DefaultsMappedStringEnum::class, DefaultsMappedStringEnum::Enum],
]);

test('should return try make default', function (string $enum, mixed $expected) {
    expect($enum::tryGet('default'))->toBe($expected);
})->with([
    [DefaultsEnum::class, DefaultsEnum::Default],
    [DefaultsIntEnum::class, DefaultsIntEnum::Default],
    [DefaultsStringEnum::class, DefaultsStringEnum::Default],
    [DefaultsConstantEnum::class, DefaultsConstantEnum::DefaultEnum],
    [DefaultsNullEnum::class, null],
    [DefaultsNullIntEnum::class, null],
    [DefaultsNullStringEnum::class, null],
    [DefaultsOverriddenEnum::class, DefaultsOverriddenEnum::Enum],
    [DefaultsOverriddenIntEnum::class, DefaultsOverriddenIntEnum::Enum],
    [DefaultsOverriddenStringEnum::class, DefaultsOverriddenStringEnum::Enum],
    [DefaultsMappedEnum::class, DefaultsMappedEnum::Enum],
    [DefaultsMappedIntEnum::class, DefaultsMappedIntEnum::Enum],
    [DefaultsMappedStringEnum::class, DefaultsMappedStringEnum::Enum],
]);

test('try from should use default', function () {
    expect(DefaultsOverriddenEnum::tryFrom('default'))->toBe(DefaultsOverriddenEnum::Enum);
});

test('from should use default', function () {
    expect(DefaultsOverriddenEnum::from('default'))->toBe(DefaultsOverriddenEnum::Enum);
});

test('is default', function ($enum, bool $expected) {
    expect($enum->isDefault())->toBe($expected);
})->with([
    [DefaultsEnum::Default, true],
    [DefaultsEnum::Enum, false],
    [DefaultsIntEnum::Default, true],
    [DefaultsIntEnum::Enum, false],
    [DefaultsStringEnum::Default, true],
    [DefaultsStringEnum::Enum, false],
    [DefaultsNullEnum::Enum, false],
    [DefaultsNullIntEnum::Enum, false],
    [DefaultsNullStringEnum::Enum, false],
    [DefaultsOverriddenEnum::Enum, true],
    [DefaultsOverriddenEnum::DefaultEnum, false],
    [DefaultsOverriddenIntEnum::Enum, true],
    [DefaultsOverriddenIntEnum::Default, false],
    [DefaultsOverriddenStringEnum::Enum, true],
    [DefaultsOverriddenStringEnum::Default, false],
    [DefaultsMappedEnum::Enum, true],
    [DefaultsMappedEnum::DefaultEnum, false],
    [DefaultsMappedIntEnum::Enum, true],
    [DefaultsMappedIntEnum::DefaultEnum, false],
    [DefaultsMappedStringEnum::Enum, true],
    [DefaultsMappedStringEnum::DefaultEnum, false],
]);

test('is not default', function ($enum, bool $expected) {
    expect($enum->isNotDefault())->toBe(!$expected);
})->with([
    [DefaultsEnum::Default, true],
    [DefaultsEnum::Enum, false],
    [DefaultsIntEnum::Default, true],
    [DefaultsIntEnum::Enum, false],
    [DefaultsStringEnum::Default, true],
    [DefaultsStringEnum::Enum, false],
    [DefaultsNullEnum::Enum, false],
    [DefaultsNullIntEnum::Enum, false],
    [DefaultsNullStringEnum::Enum, false],
    [DefaultsOverriddenEnum::Enum, true],
    [DefaultsOverriddenEnum::DefaultEnum, false],
    [DefaultsOverriddenIntEnum::Enum, true],
    [DefaultsOverriddenIntEnum::Default, false],
    [DefaultsOverriddenStringEnum::Enum, true],
    [DefaultsOverriddenStringEnum::Default, false],
    [DefaultsMappedEnum::Enum, true],
    [DefaultsMappedEnum::DefaultEnum, false],
    [DefaultsMappedIntEnum::Enum, true],
    [DefaultsMappedIntEnum::DefaultEnum, false],
    [DefaultsMappedStringEnum::Enum, true],
    [DefaultsMappedStringEnum::DefaultEnum, false],
]);