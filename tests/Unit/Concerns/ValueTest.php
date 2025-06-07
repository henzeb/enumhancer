<?php

use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Value\ValueIntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Value\ValueStrictEnum;

test('enum should return value', function ($enum, string $expected) {
    expect($enum->value())->toBe($expected);
})->with([
    'backed-1' => [EnhancedBackedEnum::ENUM, EnhancedBackedEnum::ENUM->value],
    'backed-2' => [EnhancedBackedEnum::ANOTHER_ENUM, EnhancedBackedEnum::ANOTHER_ENUM->value],
    'unit-1' => [EnhancedUnitEnum::ENUM, strtolower(EnhancedUnitEnum::ENUM->name)],
    'unit-2' => [EnhancedUnitEnum::ANOTHER_ENUM, strtolower(EnhancedUnitEnum::ANOTHER_ENUM->name)],
    'unit-3' => [ValueStrictEnum::Strict, 'Strict']
]);

test('enum should return key', function ($enum, int $expected) {
    expect($enum->key())->toBe($expected);
})->with([
    'string-backed-1' => [EnhancedBackedEnum::ENUM, 0],
    'string-backed-2' => [EnhancedBackedEnum::ANOTHER_ENUM, 1],
    'int-backed-1' => [ValueIntBackedEnum::ENUM, 64],
    'int-backed-2' => [ValueIntBackedEnum::ANOTHER_ENUM, 128],
    'unit-1' => [EnhancedUnitEnum::ENUM, 0],
    'unit-2' => [EnhancedUnitEnum::ANOTHER_ENUM, 1],
]);
