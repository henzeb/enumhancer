<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use BadMethodCallException;
use Henzeb\Enumhancer\Helpers\Enumhancer;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros\MacrosAnotherUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros\MacrosUnitEnum;
use PHPUnit\Framework\TestCase;

class MacrosTest extends TestCase
{
    public function testShouldAddMacroAndflush()
    {
        MacrosUnitEnum::macro('test', static fn() => true);

        $this->assertTrue(MacrosUnitEnum::test());

        MacrosUnitEnum::flushMacros();

        $this->expectException(BadMethodCallException::class);

        MacrosUnitEnum::test();
    }

    public function testShouldOnlyflushOwnedMacros()
    {
        MacrosUnitEnum::macro('test', static fn() => true);
        MacrosAnotherUnitEnum::macro('test', static fn() => true);
        MacrosAnotherUnitEnum::flushMacros();

        $this->assertTrue(MacrosUnitEnum::test());

        $this->expectException(BadMethodCallException::class);

        MacrosAnotherUnitEnum::test();
    }

    public function testShouldOnlyAddMacroToGivenEnum()
    {
        MacrosUnitEnum::macro('test', static fn() => true);

        $this->expectException(BadMethodCallException::class);
        MacrosAnotherUnitEnum::test();
    }

    public function testStaticMacroShouldBeBoundToEnum(): void
    {
        MacrosUnitEnum::macro('test', static fn() => self::class);
        $this->assertEquals(MacrosUnitEnum::class, MacrosUnitEnum::test());
    }

    public function testNonStaticMacroShouldBeBoundToEnum(): void
    {
        MacrosUnitEnum::macro('test', fn() => $this);
        $this->assertEquals(MacrosUnitEnum::Hearts, MacrosUnitEnum::Hearts->test());
    }

    public function testShouldOverrideMethodCaseInsensitive()
    {
        MacrosUnitEnum::macro('test', static fn() => false);
        MacrosUnitEnum::macro('TEST', static fn() => true);

        $this->assertTrue(MacrosUnitEnum::test());

    }

    public function testAllowPassingParameters()
    {
        MacrosUnitEnum::macro('test', static fn(string $string, bool $bool) => [$string, $bool]);

        $this->assertEquals(
            [
                'hello',
                true
            ],
            MacrosUnitEnum::test('hello', 1)
        );

        $this->assertEquals(
            [
                'world',
                false
            ],
            MacrosUnitEnum::Hearts->test('world', false)
        );
    }

    public function testShouldNotExecuteNonStaticMacroStatically()
    {
        MacrosUnitEnum::macro('test', fn() => true);

        $this->assertTrue(MacrosUnitEnum::Diamonds->test());

        $this->expectError();

        $this->assertTrue(MacrosUnitEnum::test());

    }

    public function testShouldExecuteStaticMacroStatically()
    {
        MacrosUnitEnum::macro('test2', static fn() => true);

        $this->assertTrue(MacrosUnitEnum::test2());

        $this->assertTrue(MacrosUnitEnum::Hearts->test2());
    }

    public function testMixin()
    {
        $mixin = new class {
            protected function test()
            {
                return static fn() => true;
            }
        };

        MacrosUnitEnum::mixin($mixin);

        $this->assertTrue(MacrosUnitEnum::test());

    }

    public function testMixinAsString()
    {
        $mixin = new class {
            protected function test()
            {
                return static fn() => true;
            }
        };

        MacrosUnitEnum::mixin($mixin::class);

        $this->assertTrue(MacrosUnitEnum::test());

    }

    public function testHasMacros()
    {
        $this->assertFalse(MacrosUnitEnum::hasMacro('test'));
        MacrosUnitEnum::macro('test', fn() => true);
        $this->assertTrue(MacrosUnitEnum::hasMacro('test'));

    }

    public function testHasMacrosGlobal()
    {
        $this->assertFalse(MacrosUnitEnum::hasMacro('test'));
        Enumhancer::macro('test', fn() => true);
        $this->assertTrue(MacrosUnitEnum::hasMacro('test'));
    }

    public function testGlobalMacroMixin()
    {
        $mixin = new class {
            protected function test()
            {
                return static fn() => true;
            }
        };

        Enumhancer::mixin($mixin::class);

        $this->assertTrue(MacrosUnitEnum::test());
    }

    protected function tearDown(): void
    {
        MacrosUnitEnum::flushMacros();
    }
}
