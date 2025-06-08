<?php

use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;

afterEach(function () {
    \Closure::bind(function(){
        EnumProperties::clearGlobal();
        EnumProperties::$properties = [];
        EnumProperties::$once = [];
    }, null, EnumProperties::class)();
});

test('set default', function () {
    DefaultsEnum::setDefault(DefaultsEnum::Enum);

    expect(EnumProperties::get(DefaultsEnum::class, EnumProperties::reservedWord('defaults')))
        ->toBe(DefaultsEnum::Enum);

    expect(DefaultsEnum::default())
        ->toBe(DefaultsEnum::Enum);
});

test('set default once', function () {
    DefaultsEnum::setDefaultOnce(DefaultsEnum::Enum);

    expect(EnumProperties::get(DefaultsEnum::class, EnumProperties::reservedWord('defaults')))
        ->toBe(DefaultsEnum::Enum);

    expect(DefaultsEnum::default())
        ->toBe(DefaultsEnum::Enum);

    DefaultsEnum::setDefault(DefaultsEnum::Default);
})->throws(PropertyAlreadyStoredException::class);
