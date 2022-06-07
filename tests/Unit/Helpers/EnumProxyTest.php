<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Helpers\EnumProxy;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;

class EnumProxyTest extends TestCase
{
    public function testShouldReturnSameName(): void {
        $this->assertEquals(
            EnhancedUnitEnum::ENUM->name,
            (new EnumProxy(EnhancedUnitEnum::ENUM, true))->name
        );
    }

    public function testShouldReturnSameValue(): void {
        $this->assertEquals(
            EnumValue::value(EnhancedUnitEnum::ENUM),
            (new EnumProxy(EnhancedUnitEnum::ENUM, false))->value
        );
    }

    public function testShouldReturnSameCasedValue(): void {
        $this->assertEquals(
            EnumValue::value(EnhancedUnitEnum::ENUM, true),
            (new EnumProxy(EnhancedUnitEnum::ENUM, true))->value
        );
    }

    public function testShouldBeStringable(): void
    {
        $this->assertEquals(
            EnumValue::value(EnhancedUnitEnum::ENUM),
            (string)(new EnumProxy(EnhancedUnitEnum::ENUM, false))
        );
    }

    public function testShouldBeStringableCasedValue(): void
    {
        $this->assertEquals(
            EnumValue::value(EnhancedUnitEnum::ENUM, true),
            (string)(new EnumProxy(EnhancedUnitEnum::ENUM, true))
        );
    }
}
