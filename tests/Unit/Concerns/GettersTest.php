<?php

use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Getters\GetUnitEnum;

test('expect value error when get null', function () {
    StringBackedGetEnum::get(null);
})->throws(ValueError::class);

test('expect value error when get unknown value', function () {
    StringBackedGetEnum::get('RANDOM_UNKNOWN_VALUE');
})->throws(ValueError::class);

test('get', function () {
    expect(StringBackedGetEnum::get('TEST'))->toBe(StringBackedGetEnum::TEST);
});

test('get str to upper', function () {
    expect(StringBackedGetEnum::get('test'))->toBe(StringBackedGetEnum::TEST);
});

test('get by value', function () {
    expect(StringBackedGetEnum::get('Different'))->toBe(StringBackedGetEnum::TEST1);
});

test('get by value str to upper', function () {
    expect(StringBackedGetEnum::get('stringtoupper'))->toBe(StringBackedGetEnum::TEST_STRING_TO_UPPER);
});

test('get by value on int backed enum', function () {
    expect(IntBackedEnum::get(0))->toBe(IntBackedEnum::TEST);
});

test('try get return null when does not exist', function () {
    expect(StringBackedGetEnum::tryGet('DOES NOT EXISTS'))->toBeNull();
});

test('try get by name', function () {
    expect(StringBackedGetEnum::tryGet('TEST'))->toBe(StringBackedGetEnum::TEST);
});

test('try get by value', function () {
    expect(StringBackedGetEnum::tryGet('different'))->toBe(StringBackedGetEnum::TEST1);
});

test('try get by value on int backed enum', function () {
    expect(IntBackedEnum::tryGet(0))->toBe(IntBackedEnum::TEST);
});

test('get array', function () {
    expect(StringBackedGetEnum::getArray(['TEST', 'different', 'stringtoupper']))->toBe([
        StringBackedGetEnum::TEST,
        StringBackedGetEnum::TEST1,
        StringBackedGetEnum::TEST_STRING_TO_UPPER
    ]);
});

test('get array with generator', function () {
    expect(StringBackedGetEnum::getArray(
        (function (): \Generator {
            yield 'TEST';
            yield 'different';
            yield 'stringtoupper';
        })()
    ))->toBe([
        StringBackedGetEnum::TEST,
        StringBackedGetEnum::TEST1,
        StringBackedGetEnum::TEST_STRING_TO_UPPER
    ]);
});

test('get array fails', function () {
    StringBackedGetEnum::getArray(['DOES_NOT_EXIST']);
})->throws(ValueError::class);

test('try get array', function () {
    expect(StringBackedGetEnum::tryArray(['TEST', 'different', 'stringtoupper', 'DOES_NOT_EXIST']))->toBe([
        StringBackedGetEnum::TEST,
        StringBackedGetEnum::TEST1,
        StringBackedGetEnum::TEST_STRING_TO_UPPER
    ]);
});

test('try get array with generator', function () {
    expect(StringBackedGetEnum::tryArray(
        (function (): \Generator {
            yield 'TEST';
            yield 'different';
            yield 'stringtoupper';
            yield 'DOES_NOT_EXIST';
        })()
    ))->toBe([
        StringBackedGetEnum::TEST,
        StringBackedGetEnum::TEST1,
        StringBackedGetEnum::TEST_STRING_TO_UPPER
    ]);
});

test('get string backed enum with integer', function () {
    expect(StringBackedGetEnum::get(1))->toBe(StringBackedGetEnum::TEST1);
});

test('get unit enum with integer', function () {
    expect(GetUnitEnum::get(0))->toBe(GetUnitEnum::Zero);
});

test('try get should return default', function () {
    expect(DefaultsEnum::tryGet('caseMissing'))->toBe(DefaultsEnum::default());
    expect(DefaultsEnum::tryArray(['caseMissing']))->toBe([DefaultsEnum::default()]);
});

test('try get should not return default', function () {
    expect(DefaultsEnum::tryGet('caseMissing', false))->toBeNull();
    expect(DefaultsEnum::tryArray(['caseMissing'], false))->toBe([]);
});