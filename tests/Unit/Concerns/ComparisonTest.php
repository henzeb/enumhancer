<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use BadMethodCallException;
use Henzeb\Enumhancer\Concerns\Comparison;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use PHPUnit\Framework\TestCase;


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

    public function testShouldMatchWithUnitEnumValue()
    {
        $this->assertTrue(
            EnhancedUnitEnum::ENUM->equals('enum')
        );
    }

    public function testShouldMatchWithUnitEnumValue2()
    {
        $this->assertTrue(
            EnhancedUnitEnum::ENUM->equals('Enum')
        );
    }

    public function testShouldMatchWithUnitEnumValueWithoutValueMethod()
    {
        $this->assertTrue(
            SubsetUnitEnum::ENUM->equals('enum')
        );
    }

    public function testShouldMatchWithIntBackedEnumValue()
    {
        $this->assertTrue(
            IntBackedEnum::TEST->equals(0)
        );
    }

    public function testShouldNotMatchWithIntBackedEnumValue()
    {
        $this->assertFalse(
            IntBackedEnum::TEST->equals(1)
        );
    }

    public function testShouldReturnTrueUsingMagicFunction()
    {
        $this->assertTrue(
            IntBackedEnum::TEST->isTest()
        );
    }

    public function testShouldFailUsingMagicFunctionThatDoesNotExist()
    {
        $this->expectException(BadMethodCallException::class);
        $this->assertFalse(
            IntBackedEnum::TEST->isClosed()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionIsNot()
    {
        $this->assertTrue(
            IntBackedEnum::TEST_2->isNotTest()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionWithValue()
    {
        $this->assertTrue(
            IntBackedEnum::TEST->is0()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionWithValueIsNot()
    {
        $this->assertTrue(
            IntBackedEnum::TEST_2->isNot0()
        );
    }

    public function testShouldReturnFalseUsingMagicFunction()
    {
        $this->assertFalse(
            IntBackedEnum::TEST->isTest_2()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionBasic()
    {
        $this->assertTrue(
            EnhancedUnitEnum::ENUM->isEnum()
        );
    }

    public function testShouldReturnTrueUsingMagicFunctionBasicIsNot()
    {
        $this->assertTrue(
            EnhancedUnitEnum::ANOTHER_ENUM->isNotEnum()
        );
    }

    public function testShouldReturnFalseUsingMagicFunctionBasic()
    {
        $this->assertFalse(
            EnhancedUnitEnum::ENUM->isAnother_Enum()
        );
    }

    public function testShouldReturnFalseUsingMagicFunctionBasicIsNot()
    {
        $this->assertFalse(
            EnhancedUnitEnum::ENUM->isNotEnum()
        );
    }

    public function testShouldThrowExceptionWhenEnumNotExistsMagicFunction()
    {
        $this->expectException(BadMethodCallException::class);
        EnhancedUnitEnum::ENUM->isDoesNotExist();
    }

    public function testShouldThrowExceptionWhenMethodNotExistsMagicFunction()
    {
        $this->expectException(BadMethodCallException::class);
        EnhancedUnitEnum::ENUM->doesNotExist();
    }

    public function testShouldWorkWithoutIssuesCallingSelf()
    {
        $this->assertTrue(EnhancedUnitEnum::ENUM->isEnumFunction());
    }

    public function testPassingNullReturnsFalse()
    {
        $this->assertFalse(EnhancedUnitEnum::ENUM->equals(null));
        $this->assertFalse(EnhancedBackedEnum::ENUM->equals(null));
    }

    public function testPassingEnums(): void
    {
        $this->assertTrue(EnhancedBackedEnum::ENUM->equals(EnhancedUnitEnum::ENUM));

        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->isMapped());

        $this->expectException(BadMethodCallException::class);

        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->isExpectedToFail());
    }

    public function testIs(): void
    {
        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->is('another_enum'));

        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->is(1));

        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->is(EnhancedUnitEnum::ANOTHER_ENUM));

        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->is('mapped'));

        $this->assertFalse(EnhancedBackedEnum::ANOTHER_ENUM->is('something else'));
    }

    public function testIsNot(): void
    {
        $this->assertFalse(EnhancedBackedEnum::ANOTHER_ENUM->isNot('another_enum'));

        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->isNot(2));

        $this->assertFalse(EnhancedBackedEnum::ANOTHER_ENUM->isNot(EnhancedUnitEnum::ANOTHER_ENUM));

        $this->assertFalse(EnhancedBackedEnum::ANOTHER_ENUM->isNot('mapped'));

        $this->assertTrue(EnhancedBackedEnum::ANOTHER_ENUM->isNot('something else'));
    }

    public function testIsIn(): void
    {
        $this->assertTrue(
            EnhancedBackedEnum::ANOTHER_ENUM->isIn('another_enum', 'somethingElse')
        );

        $this->assertTrue(
            EnhancedBackedEnum::ANOTHER_ENUM->isIn(EnhancedUnitEnum::ANOTHER_ENUM, 'somethingElse')
        );

        $this->assertTrue(
            EnhancedBackedEnum::ANOTHER_ENUM->isIn(0, 1)
        );

        $this->assertFalse(
            EnhancedBackedEnum::ANOTHER_ENUM->isIn(0, 2)
        );
    }

    public function testIsNotIn(): void
    {
        $this->assertTrue(
            EnhancedBackedEnum::ANOTHER_ENUM->isNotIn('other_enums', 'somethingElse')
        );

        $this->assertTrue(
            EnhancedBackedEnum::ANOTHER_ENUM->isNotIn(EnhancedUnitEnum::ENUM, 'somethingElse')
        );

        $this->assertTrue(
            EnhancedBackedEnum::ANOTHER_ENUM->isNotIn(0, 2)
        );

        $this->assertFalse(
            EnhancedBackedEnum::ANOTHER_ENUM->isNotIn(0, 1)
        );
    }
}
