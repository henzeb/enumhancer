<?php

use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;

afterEach(function () {
    \Closure::bind(function () {
        EnumProperties::clearGlobal();
        EnumProperties::$properties = [];
        EnumProperties::$once = [];
    }, null, EnumProperties::class)();
});


test('store should not accept non enums', function () {
    EnumProperties::store(\stdClass::class, 'property', 'value');
})->throws(\TypeError::class);

test('get should not accept non enums', function () {
    EnumProperties::get(\stdClass::class, 'property');
})->throws(\TypeError::class);

test('clear should not accept non enums', function () {
    EnumProperties::clear(\stdClass::class);
})->throws(\TypeError::class);


test('store property', function (string $key, mixed $value, mixed $expectedValue, string $storeIn, string|null $expectedStoreIn = null) {
    EnumProperties::store($storeIn, $key, $value);

    expect(EnumProperties::get($expectedStoreIn ?? $storeIn, $key))->toEqual($expectedValue);
})->with(function () {
    $callable = fn() => 'true';
    return [
        'boolean' => ['property', true, true, ConstructableUnitEnum::class],
        'object' => ['anObject', new \stdClass(), new \stdClass(), ConstructableUnitEnum::class],
        'string' => ['aString', 'A String', 'A String', ConstructableUnitEnum::class],
        'enum' => [
            'anEnum',
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::class
        ],
        'callable' => ['property', $callable, $callable, ConstructableUnitEnum::class],
        'another-enum-that-tries-to-get' => [
            'anotherProperty',
            true,
            null,
            ConstructableUnitEnum::class,
            StringBackedGetEnum::class
        ],
    ];
});

test('store property once', function (string $key, mixed $value, mixed $expectedValue, string $storeIn, string|null $expectedStoreIn = null) {
    EnumProperties::storeOnce($storeIn, $key, $value);

    expect(EnumProperties::get($expectedStoreIn ?? $storeIn, $key))->toEqual($expectedValue);

    expect(fn() => EnumProperties::storeOnce($storeIn, $key, $value))->toThrow(PropertyAlreadyStoredException::class);
})->with(function () {
    $callable = fn() => 'true';
    return [
        'boolean' => ['property', true, true, ConstructableUnitEnum::class],
        'object' => ['anObject', new \stdClass(), new \stdClass(), ConstructableUnitEnum::class],
        'string' => ['aString', 'A String', 'A String', ConstructableUnitEnum::class],
        'enum' => [
            'anEnum',
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::class
        ],
        'callable' => ['property', $callable, $callable, ConstructableUnitEnum::class],
        'another-enum-that-tries-to-get' => [
            'anotherProperty',
            true,
            null,
            ConstructableUnitEnum::class,
            StringBackedGetEnum::class
        ],
    ];
});

test('store property once and try storing', function (string $key, mixed $value, mixed $expectedValue, string $storeIn, string|null $expectedStoreIn = null) {
    EnumProperties::storeOnce($storeIn, $key, $value);

    expect(EnumProperties::get($expectedStoreIn ?? $storeIn, $key))->toEqual($expectedValue);

    expect(fn() => EnumProperties::store($storeIn, $key, $value))->toThrow(PropertyAlreadyStoredException::class);
})->with(function () {
    $callable = fn() => 'true';
    return [
        'boolean' => ['property', true, true, ConstructableUnitEnum::class],
        'object' => ['anObject', new \stdClass(), new \stdClass(), ConstructableUnitEnum::class],
        'string' => ['aString', 'A String', 'A String', ConstructableUnitEnum::class],
        'enum' => [
            'anEnum',
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::class
        ],
        'callable' => ['property', $callable, $callable, ConstructableUnitEnum::class],
        'another-enum-that-tries-to-get' => [
            'anotherProperty',
            true,
            null,
            ConstructableUnitEnum::class,
            StringBackedGetEnum::class
        ],
    ];
});

test('clears properties', function () {
    EnumProperties::store(ConstructableUnitEnum::class, 'property', 'a value');
    EnumProperties::store(StringBackedGetEnum::class, 'property', 'a value');

    EnumProperties::clear(ConstructableUnitEnum::class);

    expect(EnumProperties::get(ConstructableUnitEnum::class, 'property'))->toBeNull();
    expect(EnumProperties::get(StringBackedGetEnum::class, 'property'))->toBe('a value');
});

test('doesnt clear properties once', function () {
    EnumProperties::storeOnce(ConstructableUnitEnum::class, 'property', 'a value');
    EnumProperties::storeOnce(ConstructableUnitEnum::class, 'property2', 'a value');

    EnumProperties::clear(ConstructableUnitEnum::class);

    expect(EnumProperties::get(ConstructableUnitEnum::class, 'property'))->toBe('a value');
    expect(EnumProperties::get(ConstructableUnitEnum::class, 'property2'))->toBe('a value');
});

test('clears single property', function () {
    EnumProperties::store(ConstructableUnitEnum::class, 'property', 'a value');
    EnumProperties::store(ConstructableUnitEnum::class, 'property2', 'a value');

    EnumProperties::clear(ConstructableUnitEnum::class, 'property');

    expect(EnumProperties::get(ConstructableUnitEnum::class, 'property'))->toBeNull();
    expect(EnumProperties::get(ConstructableUnitEnum::class, 'property2'))->toBe('a value');
});

test('doesnt clear single property once', function () {
    EnumProperties::storeOnce(ConstructableUnitEnum::class, 'property', 'a value');

    EnumProperties::clear(ConstructableUnitEnum::class, 'property');

    expect(EnumProperties::get(ConstructableUnitEnum::class, 'property'))->toBe('a value');
});

test('clears global', function () {
    EnumProperties::global('globalProperty', 'a value');

    EnumProperties::clearGlobal();

    expect(EnumProperties::get(StringBackedGetEnum::class, 'globalProperty'))->toBeNull();
});

test('store globally', function (string $key, mixed $value, mixed $expectedValue) {
    EnumProperties::global($key, $value);

    expect(EnumProperties::get(StringBackedGetEnum::class, $key))->toEqual($expectedValue);
})->with(function () {
    $callable = fn() => 'true';
    return [
        'boolean' => ['property', true, true],
        'object' => ['anObject', new \stdClass(), new \stdClass()],
        'string' => ['aString', 'A String', 'A String'],
        'enum' => ['anEnum', ConstructableUnitEnum::CALLABLE, ConstructableUnitEnum::CALLABLE],
        'callable' => ['property', $callable, $callable],
    ];
});

test('if local property overrides global property', function () {
    EnumProperties::global('property', 'global value');
    EnumProperties::store(ConstructableUnitEnum::class, 'property', 'local value');

    expect(EnumProperties::get(ConstructableUnitEnum::class, 'property'))->toBe('local value');
});

test('store once overrides store', function () {
    EnumProperties::store(ConstructableUnitEnum::class, 'test', 'test');
    EnumProperties::storeOnce(ConstructableUnitEnum::class, 'test', 'something else');

    expect(EnumProperties::get(ConstructableUnitEnum::class, 'test'))->toBe('something else');
});


test('reserved words mapping', function (string $expected, string $name) {
    expect(EnumProperties::reservedWord($name))->toBe($expected);
})->with([
    ['@default_configure', 'defaults'],
    ['@labels_configure', 'labels'],
    ['@mapper_configure', 'mapper'],
    ['@state_configure', 'state'],
    ['@state_hook_configure', 'hooks'],
]);

test('reserved words when trying to store', function (string $name) {
    EnumProperties::store(ConstructableUnitEnum::class, $name, 'test');
})->with([
    '@default_configure',
    '@labels_configure',
    '@mapper_configure',
    '@state_configure',
    '@state_hook_configure',
])->throws(ReservedPropertyNameException::class);

test('reserved words when trying to store once', function (string $name) {
    EnumProperties::storeOnce(ConstructableUnitEnum::class, $name, 'test');
})->with([
    '@default_configure',
    '@labels_configure',
    '@mapper_configure',
    '@state_configure',
    '@state_hook_configure',
])->throws(ReservedPropertyNameException::class);
