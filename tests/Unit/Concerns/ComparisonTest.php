<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use BadMethodCallException;
use Henzeb\Enumhancer\Concerns\Comparison;
use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
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

    public function testWhenStringEqualsValueWithCapitals()
    {
        $this->assertTrue(
            EnhancedBackedEnum::WITH_CAPITALS->equals('THIRD enum')
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

    public function testShouldMatchWithUnitEnumValue2() {
        $this->assertTrue(
            EnhancedUnitEnum::ENUM->equals('Enum')
        );
    }

    public function testShouldMatchWithUnitEnumValueWithoutValueMethod() {
        $this->assertTrue(
            SubsetUnitEnum::ENUM->equals('enum')
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

    public function testShouldReturnTrueUsingMagicFunction() {
        $this->assertTrue(
            IntBackedEnum::TEST->isTest()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionIsNot() {
        $this->assertTrue(
            IntBackedEnum::TEST_2->isNotTest()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionWithValue() {
        $this->assertTrue(
            IntBackedEnum::TEST->is0()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionWithValueIsNot() {
        $this->assertTrue(
            IntBackedEnum::TEST_2->isNot0()
        );
    }

    public function testShouldReturnFalseUsingMagicFunction() {
        $this->assertFalse(
            IntBackedEnum::TEST->isTest_2()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionBasic() {
        $this->assertTrue(
            EnhancedUnitEnum::ENUM->isEnum()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionBasicIsNot() {
        $this->assertTrue(
            EnhancedUnitEnum::ANOTHER_ENUM->isNotEnum()
        );
    }

    public function testShouldReturnFalseUsingMagicFunctionBasic() {
        $this->assertFalse(
            EnhancedUnitEnum::ENUM->isAnother_Enum()
        );
    }

    public function testShouldReturnFalseUsingMagicFunctionBasicIsNot() {
        $this->assertFalse(
            EnhancedUnitEnum::ENUM->isNotEnum()
        );
    }

    public function testShouldThrowExceptionWhenEnumNotExistsMagicFunction() {
        $this->expectException(BadMethodCallException::class);
        EnhancedUnitEnum::ENUM->isDoesNotExist();
    }

    public function testShouldThrowExceptionWhenMethodNotExistsMagicFunction() {
        $this->expectException(BadMethodCallException::class);
        EnhancedUnitEnum::ENUM->doesNotExist();
    }

    public function testShouldWorkWithoutIssuesCallingSelf() {
        $this->assertTrue(EnhancedUnitEnum::ENUM->isEnumFunction());
    }
}
