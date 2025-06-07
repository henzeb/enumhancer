<?php

use Henzeb\Enumhancer\Helpers\EnumProxy;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use function Henzeb\Enumhancer\Functions\b;
use function Henzeb\Enumhancer\Functions\backing;
use function Henzeb\Enumhancer\Functions\backingLowercase;
use function Henzeb\Enumhancer\Functions\bl;
use function Henzeb\Enumhancer\Functions\n;
use function Henzeb\Enumhancer\Functions\name;
use function Henzeb\Enumhancer\Functions\v;
use function Henzeb\Enumhancer\Functions\value as EnumValue;
use function Henzeb\Enumhancer\Functions\valueLowercase;
use function Henzeb\Enumhancer\Functions\vl;

test('should return enum proxy', function (string $method, $enum, bool $keepValueCase = true) {
    $proxy = $method($enum, $keepValueCase);
    expect($proxy)->toBeInstanceOf(EnumProxy::class);
    expect($proxy->name)->toBe($enum->name);
    expect($proxy->value)->toBe(EnumValue::value($enum, $keepValueCase));
})->with([
    'b-unit' => ['Henzeb\Enumhancer\Functions\b', EnhancedUnitEnum::THIRD_ENUM],
    'b-backed' => ['Henzeb\Enumhancer\Functions\b', EnhancedBackedEnum::ENUM],
    'backing-unit' => ['Henzeb\Enumhancer\Functions\backing', EnhancedUnitEnum::THIRD_ENUM],
    'backing-backed' => ['Henzeb\Enumhancer\Functions\backing', EnhancedBackedEnum::ENUM],
    'bl-unit' => ['Henzeb\Enumhancer\Functions\bl', EnhancedUnitEnum::THIRD_ENUM, false],
    'bl-backed' => ['Henzeb\Enumhancer\Functions\bl', EnhancedBackedEnum::ENUM],
    'backing-lower-unit' => [
        'Henzeb\Enumhancer\Functions\backingLowercase',
        EnhancedUnitEnum::THIRD_ENUM,
        false
    ],
    'backing-lower-backed' => ['Henzeb\Enumhancer\Functions\backingLowercase', EnhancedBackedEnum::ENUM],
]);

test('backing should allow null', function () {
    expect(b(null))->toBeNull();
    expect(bl(null))->toBeNull();
    expect(backing(null))->toBeNull();
    expect(backingLowercase(null))->toBeNull();
});

test('name should return name', function (string $method, $enum) {
    expect($method($enum))->toBe($enum->name);
})->with([
    'n-unit' => ['Henzeb\Enumhancer\Functions\n', EnhancedUnitEnum::THIRD_ENUM],
    'n-backed' => ['Henzeb\Enumhancer\Functions\n', EnhancedBackedEnum::ENUM],
    'name-unit' => ['Henzeb\Enumhancer\Functions\name', EnhancedUnitEnum::THIRD_ENUM],
    'name-backed' => ['Henzeb\Enumhancer\Functions\name', EnhancedBackedEnum::ENUM],
]);

test('name should allow null', function () {
    expect(n(null))->toBeNull();
    expect(name(null))->toBeNull();
});

test('value should return value', function (string $method, $enum, bool $keepValueCase = true) {
    expect($method($enum, $keepValueCase))->toBe(EnumValue::value($enum, $keepValueCase));
})->with([
    'v-unit' => ['Henzeb\Enumhancer\Functions\v', EnhancedUnitEnum::THIRD_ENUM],
    'v-backed' => ['Henzeb\Enumhancer\Functions\v', EnhancedBackedEnum::ENUM],
    'vl-unit' => ['Henzeb\Enumhancer\Functions\vl', EnhancedUnitEnum::THIRD_ENUM, false],
    'vl-backed' => ['Henzeb\Enumhancer\Functions\vl', EnhancedBackedEnum::ENUM, false],
    'value-unit' => ['Henzeb\Enumhancer\Functions\value', EnhancedUnitEnum::THIRD_ENUM],
    'value-backed' => ['Henzeb\Enumhancer\Functions\value', EnhancedBackedEnum::ENUM],
    'value-lower-unit' => ['Henzeb\Enumhancer\Functions\valueLowercase', EnhancedUnitEnum::THIRD_ENUM, false],
    'value-lower-backed' => ['Henzeb\Enumhancer\Functions\valueLowercase', EnhancedBackedEnum::ENUM, false],
]);

test('value should allow null', function () {
    expect(v(null))->toBeNull();
    expect(vl(null))->toBeNull();
    expect(EnumValue(null))->toBeNull();
    expect(valueLowercase(null))->toBeNull();
});