<?php

use Henzeb\Enumhancer\Helpers\Enumhancer;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros\MacrosAnotherUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros\MacrosUnitEnum;

beforeEach(function () {
    set_error_handler(static function (int $errno, string $errstr): never {
        throw new \Exception($errstr, $errno);
    }, E_USER_ERROR);
});

afterEach(function () {
    MacrosUnitEnum::flushMacros();
    restore_error_handler();
});

test('should add macro and flush', function () {
    MacrosUnitEnum::macro('test', static fn() => true);

    expect(MacrosUnitEnum::test())->toBeTrue();

    MacrosUnitEnum::flushMacros();

    expect(fn() => MacrosUnitEnum::test())->toThrow(\BadMethodCallException::class);
});

test('should only flush owned macros', function () {
    MacrosUnitEnum::macro('test', static fn() => true);
    MacrosAnotherUnitEnum::macro('test', static fn() => true);
    MacrosAnotherUnitEnum::flushMacros();

    expect(MacrosUnitEnum::test())->toBeTrue();

    expect(fn() => MacrosAnotherUnitEnum::test())->toThrow(\BadMethodCallException::class);
});

test('should only add macro to given enum', function () {
    MacrosUnitEnum::macro('test', static fn() => true);

    expect(fn() => MacrosAnotherUnitEnum::test())->toThrow(\BadMethodCallException::class);
});

test('static macro should be bound to enum', function () {
    MacrosUnitEnum::macro('test', static fn() => self::class);
    expect(MacrosUnitEnum::test())->toBe(MacrosUnitEnum::class);
});

test('non static macro should be bound to enum', function () {
    MacrosUnitEnum::macro('test', fn() => $this);
    expect(MacrosUnitEnum::Hearts->test())->toBe(MacrosUnitEnum::Hearts);
});

test('should override method case insensitive', function () {
    MacrosUnitEnum::macro('test', static fn() => false);
    MacrosUnitEnum::macro('TEST', static fn() => true);

    expect(MacrosUnitEnum::test())->toBeTrue();
});

test('allow passing parameters', function () {
    MacrosUnitEnum::macro('test', static fn(string $string, bool $bool) => [$string, $bool]);

    expect(MacrosUnitEnum::test('hello', 1))->toBe([
        'hello',
        true
    ]);

    expect(MacrosUnitEnum::Hearts->test('world', false))->toBe([
        'world',
        false
    ]);
});

test('should not execute non static macro statically', function () {
    MacrosUnitEnum::macro('test', fn() => true);

    expect(MacrosUnitEnum::Diamonds->test())->toBeTrue();

    expect(fn() => MacrosUnitEnum::test())->toThrow(\Exception::class);
});

test('should execute static macro statically', function () {
    MacrosUnitEnum::macro('test2', static fn() => true);

    expect(MacrosUnitEnum::test2())->toBeTrue();
    expect(MacrosUnitEnum::Hearts->test2())->toBeTrue();
});

test('mixin', function () {
    $mixin = new class {
        protected function test()
        {
            return static fn() => true;
        }
    };

    MacrosUnitEnum::mixin($mixin);

    expect(MacrosUnitEnum::test())->toBeTrue();
});

test('mixin as string', function () {
    $mixin = new class {
        protected function test()
        {
            return static fn() => true;
        }
    };

    MacrosUnitEnum::mixin($mixin::class);

    expect(MacrosUnitEnum::test())->toBeTrue();
});

test('has macros', function () {
    expect(MacrosUnitEnum::hasMacro('test'))->toBeFalse();
    MacrosUnitEnum::macro('test', fn() => true);
    expect(MacrosUnitEnum::hasMacro('test'))->toBeTrue();
});

test('has macros global', function () {
    expect(MacrosUnitEnum::hasMacro('test'))->toBeFalse();
    Enumhancer::macro('test', fn() => true);
    expect(MacrosUnitEnum::hasMacro('test'))->toBeTrue();
});

test('global macro mixin', function () {
    $mixin = new class {
        protected function test()
        {
            return static fn() => true;
        }
    };

    Enumhancer::mixin($mixin::class);

    expect(MacrosUnitEnum::test())->toBeTrue();
});
