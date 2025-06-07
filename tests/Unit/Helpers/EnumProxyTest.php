<?php

use Henzeb\Enumhancer\Helpers\EnumProxy;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;

test('should return same name', function () {
    expect((new EnumProxy(EnhancedUnitEnum::ENUM, true))->name)
        ->toBe(EnhancedUnitEnum::ENUM->name);
});

test('should return same value', function () {
    expect((new EnumProxy(EnhancedUnitEnum::ENUM, false))->value)
        ->toBe(EnumValue::value(EnhancedUnitEnum::ENUM));
});

test('should return same cased value', function () {
    expect((new EnumProxy(EnhancedUnitEnum::ENUM, true))->value)
        ->toBe(EnumValue::value(EnhancedUnitEnum::ENUM, true));
});

test('should be stringable', function () {
    expect((string)(new EnumProxy(EnhancedUnitEnum::ENUM, false)))
        ->toBe(EnumValue::value(EnhancedUnitEnum::ENUM));
});

test('should be stringable cased value', function () {
    expect((string)(new EnumProxy(EnhancedUnitEnum::ENUM, true)))
        ->toBe(EnumValue::value(EnhancedUnitEnum::ENUM, true));
});
