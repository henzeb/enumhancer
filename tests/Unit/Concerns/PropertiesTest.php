<?php

use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Helpers\ClearsEnumProperties;

afterEach(function () {
    \Closure::bind(function () {
        EnumProperties::clearGlobal();
        EnumProperties::$properties = [];
        EnumProperties::$once = [];
    }, null, EnumProperties::class)();
});

test('set property', function () {
    EnhancedBackedEnum::property('MyProperty', 'A Value');

    expect(EnumProperties::get(EnhancedBackedEnum::class, 'MyProperty'))->toBe('A Value');
});

test('set property overrides global', function () {
    EnumProperties::global('MyProperty', 'A global Value');
    EnhancedBackedEnum::property('MyProperty', 'A Value');

    expect(EnumProperties::get(EnhancedBackedEnum::class, 'MyProperty'))->toBe('A Value');
});

test('get property', function () {
    EnumProperties::clearGlobal();

    EnhancedBackedEnum::property('MyProperty', 'A Value');

    expect(EnhancedBackedEnum::property('MyProperty'))->toBe('A Value');
});

test('unset property', function () {
    EnumProperties::clearGlobal();
    EnhancedBackedEnum::property('MyProperty', 'A Value');
    EnhancedBackedEnum::unset('MyProperty');

    expect(EnhancedBackedEnum::property('MyProperty'))->toBeNull();
});

test('does not unset global property', function () {
    EnumProperties::clearGlobal();
    EnumProperties::store(EnhancedBackedEnum::class, 'property', 'local property');
    EnumProperties::global( 'property', 'global property');

    EnhancedBackedEnum::unset('property');

    expect(EnhancedBackedEnum::property('property'))->toBe('global property');
});

test('unset all local properties', function () {
    EnumProperties::clearGlobal();
    EnhancedBackedEnum::unsetAll();
    EnumProperties::global('globalProperty', 'a global property');
    EnhancedBackedEnum::property('MyProperty', 'A Value');
    EnhancedBackedEnum::property('MyProperty2', 'Another Value');
    EnhancedBackedEnum::unsetAll();

    expect(EnhancedBackedEnum::property('globalProperty'))->toBe('a global property');
    expect(EnhancedBackedEnum::property('MyProperty'))->toBeNull();
    expect(EnhancedBackedEnum::property('MyProperty2'))->toBeNull();
});
