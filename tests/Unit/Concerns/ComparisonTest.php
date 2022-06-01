<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\Comparison;
use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;


class ComparisonTest extends TestCase
{
    public function testEnumEquals()
    {
        $this->assertTrue(
            EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ENUM)
        );
    }

    public function testEnumNotEquals()
    {
        $this->assertFalse(
            EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ANOTHER_ENUM)
        );
    }

    /** @noinspection PhpExpressionResultUnusedInspection */
    public function testEqualsDoesNotAcceptDifferentObject()
    {
        $class = new class {
            use Comparison;
        };
        $this->expectError();

        EnhancedBackedEnum::ENUM->equals($class);
    }

    public function testWhenMultipleValuesAreGivenAndOneIsTrue()
    {
        $this->assertTrue(
            EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ANOTHER_ENUM, EnhancedBackedEnum::ENUM)
        );
    }

    public function testWhenMultipleValuesAreGivenAndNoneIsTrue()
    {
        $this->assertFalse(
            EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ANOTHER_ENUM, EnhancedBackedEnum::ANOTHER_ENUM)
        );
    }

    public function testWhenStringEqualsName()
    {
        $this->assertTrue(
            EnhancedBackedEnum::ENUM->equals('ENUM')
        );
    }

    public function testWhenStringNotEqualsName()
    {
        $this->assertFalse(
            EnhancedBackedEnum::ENUM->equals('TEST2')
        );
    }

    public function testWhenStringEqualsValue()
    {
        $this->assertTrue(
            EnhancedBackedEnum::ENUM->equals('an enum')
        );
    }

    public function testWhenStringNotEqualsValue()
    {
        $this->assertFalse(
            EnhancedBackedEnum::ENUM->equals('not an enum')
        );
    }

    public function testShouldMatchWithUnitEnumValue() {
        $this->assertTrue(
            EnhancedUnitEnum::ENUM->equals('enum')
        );
    }

    public function testShouldMatchWithIntBackedEnumValue() {
        $this->assertTrue(
            IntBackedEnum::TEST->equals(0)
        );
    }

    public function testShouldNotMatchWithIntBackedEnumValue() {
        $this->assertFalse(
            IntBackedEnum::TEST->equals(1)
        );
    }
}
