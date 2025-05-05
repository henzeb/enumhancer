<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use Henzeb\Enumhancer\Concerns\Dropdown;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Helpers\Subset\EnumSubsetMethods;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Unit\Concerns\DropdownTest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TypeError;

class EnumSubsetMethodsTest extends TestCase
{

    public function testShouldThrowErrorWithWrongEnumType(): void
    {
        $this->expectException(TypeError::class);
        (new EnumSubsetMethods(IntBackedEnum::class, EnhancedUnitEnum::ENUM));
    }


    public function testEqualsShouldReturnFalseWhenNoEnumsPassed()
    {
        $this->assertFalse(
            (new EnumSubsetMethods(IntBackedEnum::class))
                ->equals()
        );
    }

    public function testEqualsShouldReturnFalseWhenNullPassed()
    {
        $this->assertFalse(
            (new EnumSubsetMethods(IntBackedEnum::class))
                ->equals(null)
        );
    }

    public function testEqualsShouldReturnTrue()
    {
        $this->assertTrue(
            (new EnumSubsetMethods(IntBackedEnum::class, IntBackedEnum::TEST))
                ->equals(IntBackedEnum::TEST)
        );
    }

    public function testEqualsMultiShouldReturnTrue()
    {
        $this->assertTrue(
            (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
                ->equals(IntBackedEnum::TEST)
        );
    }

    public function testEqualsMultiWithNullShouldReturnTrue()
    {
        $this->assertTrue(
            (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
                ->equals(IntBackedEnum::TEST, null)
        );
    }

    public function testNamesShouldReturnArrayOfNames()
    {
        $this->assertEquals(
            $this->getNames(IntBackedEnum::cases()),
            (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
                ->names()
        );
    }

    public function testValueShouldReturnArrayOfValuesStringBacked()
    {
        $this->assertEquals(
            $this->getValues(StringBackedGetEnum::cases()),
            (new EnumSubsetMethods(StringBackedGetEnum::class, ...StringBackedGetEnum::cases()))
                ->values()
        );
    }

    public function testValueShouldReturnArrayOfValuesIntBacked()
    {
        $this->assertEquals(
            $this->getValues(IntBackedEnum::cases()),
            (new EnumSubsetMethods(IntBackedEnum::class, ...IntBackedEnum::cases()))
                ->values()
        );
    }

    public function testValueShouldReturnArrayOfValuesUnitEnums()
    {
        $this->assertEquals(
            $this->getValues(EnhancedUnitEnum::cases()),
            (new EnumSubsetMethods(EnhancedUnitEnum::class, ...EnhancedUnitEnum::cases()))
                ->values()
        );
    }

    public function testValueShouldReturnArrayOfValuesUnitEnumsWithoutValue()
    {
        $this->assertEquals(
            $this->getValues(SubsetUnitEnum::cases()),
            (new EnumSubsetMethods(SubsetUnitEnum::class, ...SubsetUnitEnum::cases()))
                ->values()
        );
    }

    public function testShouldRunClosureOnArrayOfEnums()
    {
        $enums = [];
        (new EnumSubsetMethods(EnhancedUnitEnum::class, ...EnhancedUnitEnum::cases()))
            ->do(
                function (EnhancedUnitEnum $enum) use (&$enums) {
                    $enums[] = $enum;
                }
            );
        $this->assertEquals(EnhancedUnitEnum::cases(), $enums);
    }

    public static function providesTestCasesForReturningSubsetOfCases(): array
    {
        return [
            [[EnhancedUnitEnum::ENUM]],
            [[EnhancedUnitEnum::ENUM, EnhancedUnitEnum::THIRD_ENUM]]
        ];
    }

    #[DataProvider("providesTestCasesForReturningSubsetOfCases")]
    public function testCasesShouldReturnSubsetOfCases(array $cases)
    {
        $this->assertEquals(
            $cases,
            (new EnumSubsetMethods(EnhancedUnitEnum::class, ...$cases))->cases()
        );
    }

    public static function providesDropdownTestcases(): array
    {
        return DropdownTest::providesDropdownTestcases();
    }

    /**
     * @param string $enum
     * @param array $expected
     * @param bool $keepCase
     * @return void
     */
    #[DataProvider("providesDropdownTestcases")]
    public function testDropdown(string $enum, array $expected, bool $keepCase = false)
    {
        /**
         * @var $enum Dropdown|string
         */
        $this->assertEquals($expected, (new EnumSubsetMethods($enum, ...$enum::cases()))->dropdown($keepCase));
    }

    private function getNames(array $cases): array
    {
        return array_map(fn($enum) => $enum->name, $cases);
    }

    private function getValues(array $cases): array
    {
        return array_map(fn($enum) => EnumValue::value($enum), $cases);
    }
}
