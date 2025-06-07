<?php

use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\From\FromWithMappersEnum;

test('non backed enum can call from', function () {
    expect(ConstructableUnitEnum::from('callable'))
        ->toBe(ConstructableUnitEnum::CALLABLE);
});

test('unit enum can call from and fail', function () {
    ConstructableUnitEnum::from('doesnotexist');
})->throws(ValueError::class);

test('unit enum can call try from', function () {
    expect(ConstructableUnitEnum::tryFrom('callable'))
        ->toBe(ConstructableUnitEnum::CALLABLE);
});

test('try from should return null', function () {
    expect(ConstructableUnitEnum::tryFrom('doesNotExist'))
        ->toBeNull();
});

test('allow enum as value', function () {
    expect(ConstructableUnitEnum::tryFrom(ConstructableUnitEnum::CALLABLE))
        ->toBe(ConstructableUnitEnum::CALLABLE);

    expect(ConstructableUnitEnum::from(ConstructableUnitEnum::CALLABLE))
        ->toBe(ConstructableUnitEnum::CALLABLE);
});

test('allow mapping when passing enum', function () {
    expect(FromWithMappersEnum::tryFrom(StringBackedGetEnum::Translated))
        ->toBe(FromWithMappersEnum::NotTranslated);

    expect(FromWithMappersEnum::tryFrom(StringBackedGetEnum::TEST))
        ->toBeNull();

    expect(FromWithMappersEnum::from(StringBackedGetEnum::Translated))
        ->toBe(FromWithMappersEnum::NotTranslated);
});
