<?php

namespace Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Helpers\EnumSubsetMethods;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedMakersEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedMakersEnum;

class EnumSubsetMethodsTest extends TestCase
{

    public function testShouldThrowErrorWithWrongEnumType(): void
    {
        $this->expectError();
        (new EnumSubsetMethods(IntBackedMakersEnum::class, EnhancedUnitEnum::ENUM));
    }


    public function testEqualsShouldReturnNullWhenNoEnumsPassed()
    {
        $this->assertFalse(
            (new EnumSubsetMethods(IntBackedMakersEnum::class))
                ->equals(IntBackedMakersEnum::TEST)
        );
    }

    public function testEqualsShouldReturnTrue()
    {
        $this->assertTrue(
            (new EnumSubsetMethods(IntBackedMakersEnum::class, IntBackedMakersEnum::TEST))
                ->equals(IntBackedMakersEnum::TEST)
        );
    }

    public function testEqualsMultiShouldReturnTrue()
    {
        $this->assertTrue(
            (new EnumSubsetMethods(IntBackedMakersEnum::class, ...IntBackedMakersEnum::cases()))
                ->equals(IntBackedMakersEnum::TEST)
        );
    }

    public function testNamesShouldReturnArrayOfNames()
    {
        $this->assertEquals(
            $this->getNames(IntBackedMakersEnum::cases()),
            (new EnumSubsetMethods(IntBackedMakersEnum::class, ...IntBackedMakersEnum::cases()))
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
            $this->getValues(IntBackedMakersEnum::cases()),
            (new EnumSubsetMethods(IntBackedMakersEnum::class, ...IntBackedMakersEnum::cases()))
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

    private function getNames(array $cases): array
    {
        return array_map(fn($enum) => $enum->name, $cases);
    }

    private function getValues(array $cases): array
    {
        return array_map(fn($enum) => $enum->value ?? $enum->value(), $cases);
    }
}
