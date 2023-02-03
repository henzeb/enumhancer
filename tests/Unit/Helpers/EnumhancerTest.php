<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\Enumhancer;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros\MacrosUnitEnum;
use PHPUnit\Framework\TestCase;

class EnumhancerTest extends TestCase
{
    public function testShouldFlushGlobalMacros()
    {
        Enumhancer::macro('globalMacro', fn() => true);
        MacrosUnitEnum::macro('localMacro', fn() => true);
        Enumhancer::flushMacros();

        $this->assertFalse(MacrosUnitEnum::hasMacro('globalMacro'));
        $this->assertTrue(MacrosUnitEnum::hasMacro('localMacro'));
    }

    public function testSetReporter(): void
    {
        $reporter = new class implements Reporter {

            public function report(string $enum, ?string $key, ?BackedEnum $context): void
            {
                // TODO: Implement report() method.
            }
        };

        Enumhancer::setReporter(
            $reporter
        );

        $this->assertTrue(EnumReporter::get() === $reporter);
    }

    public function testSetProperty(): void
    {
        Enumhancer::property('test', fn() => true);

        $this->assertTrue(Enumhancer::property('test')());

        $this->assertTrue(EnhancedBackedEnum::property('test')());
    }

    public function testSetPropertyGlobalAndLocal(): void
    {
        EnhancedBackedEnum::property('test', fn() => true);
        Enumhancer::property('test', fn() => false);

        $this->assertTrue(EnhancedBackedEnum::property('test')());

    }

    public function testUnsetProperty(): void
    {
        Enumhancer::property('test', fn() => true);
        EnhancedBackedEnum::property('test', fn() => false);

        Enumhancer::unsetProperty('test');

        $this->assertFalse(EnhancedBackedEnum::property('test')());

    }

    public function testFlushGlobal(): void
    {
        Enumhancer::property('test', fn() => true);
        EnhancedBackedEnum::property('test', fn() => false);

        Enumhancer::clearProperties();

        $this->assertFalse(EnhancedBackedEnum::property('test')());

    }
}
