<?php

use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\Enumhancer;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros\MacrosUnitEnum;
test('should flush global macros', function () {
    Enumhancer::macro('globalMacro', fn() => true);
    MacrosUnitEnum::macro('localMacro', fn() => true);
    Enumhancer::flushMacros();

    expect(MacrosUnitEnum::hasMacro('globalMacro'))->toBeFalse();
    expect(MacrosUnitEnum::hasMacro('localMacro'))->toBeTrue();
});

test('set reporter', function () {
    $reporter = new class implements Reporter {

        public function report(string $enum, ?string $key, ?\BackedEnum $context): void
        {
            // TODO: Implement report() method.
        }
    };

    Enumhancer::setReporter(
        $reporter
    );

    expect(EnumReporter::get() === $reporter)->toBeTrue();
});

test('set property', function () {
    Enumhancer::property('test', fn() => true);

    expect(Enumhancer::property('test')())->toBeTrue();

    expect(EnhancedBackedEnum::property('test')())->toBeTrue();
});

test('set property global and local', function () {
    EnhancedBackedEnum::property('test', fn() => true);
    Enumhancer::property('test', fn() => false);

    expect(EnhancedBackedEnum::property('test')())->toBeTrue();
});

test('unset property', function () {
    Enumhancer::property('test', fn() => true);
    EnhancedBackedEnum::property('test', fn() => false);

    Enumhancer::unsetProperty('test');

    expect(EnhancedBackedEnum::property('test')())->toBeFalse();
});

test('flush global', function () {
    Enumhancer::property('test', fn() => true);
    EnhancedBackedEnum::property('test', fn() => false);

    Enumhancer::clearProperties();

    expect(EnhancedBackedEnum::property('test')())->toBeFalse();
});
