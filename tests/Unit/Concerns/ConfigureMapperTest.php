<?php

use Henzeb\Enumhancer\Contracts\Mapper;
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

test('set mapper', function () {
    $mapper = new class extends Mapper {
        protected function mappable(): array
        {
            return [
                'set' => ConfigureEnum::Configured
            ];
        }
    };

    ConfigureEnum::setMapper($mapper);

    expect(ConfigureEnum::property(EnumProperties::reservedWord('mapper')))->toBe([$mapper]);
    expect(ConfigureEnum::get('set'))->toBe(ConfigureEnum::Configured);
});

test('set mapper once', function () {
    $mapper = new class extends Mapper {
        protected function mappable(): array
        {
            return [
                'set' => ConfigureEnum::Configured
            ];
        }
    };

    ConfigureEnum::setMapperOnce($mapper);

    expect(ConfigureEnum::property(EnumProperties::reservedWord('mapper')))->toBe([$mapper]);
    expect(ConfigureEnum::get('set'))->toBe(ConfigureEnum::Configured);

    expect(fn() => ConfigureEnum::setMapper(new class extends Mapper {
        protected function mappable(): array
        {
            return [];
        }
    }))->toThrow(PropertyAlreadyStoredException::class);
});
