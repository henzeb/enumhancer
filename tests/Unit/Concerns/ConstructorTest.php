<?php

use Henzeb\Enumhancer\Tests\Fixtures\IntBackedStaticCallableEnum;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedStaticCallableEnum;

test('should get enum using static call', function () {
    expect(ConstructableUnitEnum::CALLABLE())
        ->toBe(ConstructableUnitEnum::CALLABLE);
});

test('should fail using static call to unknown enum', function () {
    ConstructableUnitEnum::CANNOT_CALL();
})->throws(\BadMethodCallException::class);

test('should get string backed enum by name', function () {
    expect(StringBackedStaticCallableEnum::CALLABLE())
        ->toBe(StringBackedStaticCallableEnum::CALLABLE);
});

test('should get string backed enum by value', function () {
    expect(StringBackedStaticCallableEnum::gets_callable())
        ->toBe(StringBackedStaticCallableEnum::CALLABLE);
});

test('should get int backed enum by value', function () {
    $method = '0';
    expect(IntBackedStaticCallableEnum::$method())
        ->toBe(IntBackedStaticCallableEnum::CALLABLE);
});
