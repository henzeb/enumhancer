<?php

use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;

test('make should fail with invalid class', function () {
    EnumGetters::get(stdClass::class, 'test');
})->throws(TypeError::class);

test('try make should fail with invalid class', function () {
    EnumGetters::tryGet(stdClass::class, 'test');
})->throws(TypeError::class);

test('make array should fail with invalid class', function () {
    EnumGetters::getArray(stdClass::class, ['test']);
})->throws(TypeError::class);

test('try make array should fail with invalid class', function () {
    EnumGetters::tryArray(stdClass::class, ['test']);
})->throws(TypeError::class);

test('try cast returns null', function () {
    expect(EnumGetters::tryCast(EnhancedUnitEnum::class, 'DoesnotExist'))
        ->toBeNull();
});

test('try cast', function () {
    expect(EnumGetters::tryCast(EnhancedUnitEnum::class, 'ENUM'))
        ->toBe(EnhancedUnitEnum::ENUM);
});

test('try cast already enum', function () {
    expect(EnumGetters::tryCast(EnhancedUnitEnum::class, EnhancedUnitEnum::ENUM))
        ->toBe(EnhancedUnitEnum::ENUM);
});
