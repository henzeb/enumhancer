<?php

use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Configure\ConfigureEnum;

afterEach(function () {
    \Closure::bind(function () {
        EnumProperties::clearGlobal();
        EnumProperties::$properties = [];
        EnumProperties::$once = [];
    }, null, EnumProperties::class)();
});

test('set labels', function () {
    $expected = [
        ConfigureEnum::Configured->name => 'Yes',
        ConfigureEnum::NotConfigured->name => 'No'
    ];

    ConfigureEnum::setLabels($expected);

    expect(ConfigureEnum::labels())->toBe($expected);
    expect(ConfigureEnum::property(EnumProperties::reservedWord('labels')))->toBe($expected);
    expect(ConfigureEnum::Configured->label())->toBe('Yes');
});

test('set labels once', function () {
    $expected = [
        ConfigureEnum::Configured->name => 'Yes',
        ConfigureEnum::NotConfigured->name => 'No'
    ];

    ConfigureEnum::setLabelsOnce($expected);

    expect(ConfigureEnum::labels())->toBe($expected);
    expect(ConfigureEnum::Configured->label())->toBe('Yes');
    expect(ConfigureEnum::property(EnumProperties::reservedWord('labels')))->toBe($expected);

    expect(fn() => ConfigureEnum::setLabels([]))
        ->toThrow(PropertyAlreadyStoredException::class);
});
