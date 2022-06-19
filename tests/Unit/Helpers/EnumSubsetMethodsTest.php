<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Helpers\EnumSubsetMethods;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedMakersEnum;

class EnumSubsetMethodsTest extends TestCase
{

    public function testShouldThrowErrorWithWrongEnumType(): void
    {
        $this->expectError();
        (new EnumSubsetMethods(IntBackedEnum::class, EnhancedUnitEnum::ENUM));
    }


    public function testEqualsShouldReturnNullWhenNoEnumsPassed()
    {
        $this->assertFalse(
            (new EnumSubsetMethods(IntBackedEnum::class))
                ->equals(IntBackedEnum::TEST)
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
            $this->getValues(StringBackedMakersEnum::cases()),
            (new EnumSubsetMethods(StringBackedMakersEnum::class, ...StringBackedMakersEnum::cases()))
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

    public function providesTestCasesForReturningSubsetOfCases(): array
    {
        return [
            [[EnhancedUnitEnum::ENUM]],
            [[EnhancedUnitEnum::ENUM, EnhancedUnitEnum::THIRD_ENUM]]
        ];
    }

    /**
     * @return void
     * @dataProvider providesTestCasesForReturningSubsetOfCases
     */
    public function testCasesShouldReturnSubsetOfCases(array $cases)
    {
        $this->assertEquals(
            $cases,
            (new EnumSubsetMethods(EnhancedUnitEnum::class, ...$cases))->cases()
        );
    }

    private function getNames(array $cases): array
    {
        return array_map(fn($enum) => $enum->name, $cases);
    }

    private function getValues(array $cases): array
    {
        return array_map(fn($enum) => $enum->value ?? (method_exists($enum, 'value')?$enum->value():null) ?? $enum->name, $cases);
    }
}
