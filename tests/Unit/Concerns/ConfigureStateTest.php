<?php

use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Configure\ConfigureEnum;

afterEach(function () {
    \Closure::bind(function () {
        EnumProperties::clearGlobal();
        EnumProperties::$properties = [];
        EnumProperties::$once = [];
    }, null, EnumProperties::class)();
});

test('set transition hook', function () {
    $hook = new class extends TransitionHook {
        public function allowsConfiguredNotConfigured(): bool
        {
            return false;
        }
    };

    ConfigureEnum::setTransitionHook($hook);

    expect(EnumProperties::get(ConfigureEnum::class, EnumProperties::reservedWord('hooks')))->toBe($hook);

    expect(
        ConfigureEnum::Configured->isTransitionAllowed(ConfigureEnum::NotConfigured)
    )->toBeFalse();
});

test('set transition hook once', function () {
    $hook = new class extends TransitionHook {
        public function allowsConfiguredNotConfigured(): bool
        {
            return false;
        }
    };

    ConfigureEnum::setTransitionHookOnce($hook);

    expect(EnumProperties::get(ConfigureEnum::class, EnumProperties::reservedWord('hooks')))->toBe($hook);

    expect(ConfigureEnum::Configured->isTransitionAllowed(ConfigureEnum::NotConfigured))->toBeFalse();

    ConfigureEnum::setTransitionHook(new class extends TransitionHook{});
})->throws(PropertyAlreadyStoredException::class);

test('set transitions', function () {
    $expected = [
        ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
        ConfigureEnum::Configured->name => ConfigureEnum::NotConfigured,
    ];

    ConfigureEnum::setTransitions([
        ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
    ]);

    expect(ConfigureEnum::transitions())->toEqual($expected);
});

test('set transitions once', function () {
    $expected = [
        ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
        ConfigureEnum::Configured->name => ConfigureEnum::NotConfigured,
    ];

    ConfigureEnum::setTransitionsOnce([
        ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
    ]);

    expect(ConfigureEnum::transitions())->toEqual($expected);

    ConfigureEnum::setTransitions([]);
})->throws(PropertyAlreadyStoredException::class);
