<?php

namespace Henzeb\Enumhancer\Tests\Unit\Functions;

use Henzeb\Enumhancer\Helpers\EnumProxy;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use UnitEnum;
use function Henzeb\Enumhancer\Functions\b;
use function Henzeb\Enumhancer\Functions\backing;
use function Henzeb\Enumhancer\Functions\backingLowercase;
use function Henzeb\Enumhancer\Functions\bl;
use function Henzeb\Enumhancer\Functions\n;
use function Henzeb\Enumhancer\Functions\name;
use function Henzeb\Enumhancer\Functions\v;
use function Henzeb\Enumhancer\Functions\value as EnumValue;
use function Henzeb\Enumhancer\Functions\valueLowercase;
use function Henzeb\Enumhancer\Functions\vl;


class FunctionsTest extends TestCase
{
    public static function providesBackedFunctionTestcases(): array
    {
        return [
            'b-unit' => ['Henzeb\Enumhancer\Functions\b', EnhancedUnitEnum::THIRD_ENUM],
            'b-backed' => ['Henzeb\Enumhancer\Functions\b', EnhancedBackedEnum::ENUM],
            'backing-unit' => ['Henzeb\Enumhancer\Functions\backing', EnhancedUnitEnum::THIRD_ENUM],
            'backing-backed' => ['Henzeb\Enumhancer\Functions\backing', EnhancedBackedEnum::ENUM],
            'bl-unit' => ['Henzeb\Enumhancer\Functions\bl', EnhancedUnitEnum::THIRD_ENUM, false],
            'bl-backed' => ['Henzeb\Enumhancer\Functions\bl', EnhancedBackedEnum::ENUM],
            'backing-lower-unit' => [
                'Henzeb\Enumhancer\Functions\backingLowercase',
                EnhancedUnitEnum::THIRD_ENUM,
                false
            ],
            'backing-lower-backed' => ['Henzeb\Enumhancer\Functions\backingLowercase', EnhancedBackedEnum::ENUM],
        ];
    }

    #[DataProvider("providesBackedFunctionTestcases")]
    public function testShouldReturnEnumProxy(string $method, ?UnitEnum $enum, bool $keepValueCase = true)
    {
        $proxy = $method($enum, $keepValueCase);
        $this->assertInstanceOf(
            EnumProxy::class,
            $proxy
        );
        $this->assertEquals($enum->name, $proxy->name);
        $this->assertEquals(EnumValue::value($enum, $keepValueCase), $proxy->value);
    }

    public function testBackingShouldAllowNull()
    {
        $this->assertNull(
            b(null)
        );

        $this->assertNull(
            bl(null)
        );

        $this->assertNull(
            backing(null)
        );

        $this->assertNull(
            backingLowercase(null)
        );
    }


    public static function providesNameFunctionTestcases(): array
    {
        return [
            'n-unit' => ['Henzeb\Enumhancer\Functions\n', EnhancedUnitEnum::THIRD_ENUM],
            'n-backed' => ['Henzeb\Enumhancer\Functions\n', EnhancedBackedEnum::ENUM],
            'name-unit' => ['Henzeb\Enumhancer\Functions\name', EnhancedUnitEnum::THIRD_ENUM],
            'name-backed' => ['Henzeb\Enumhancer\Functions\name', EnhancedBackedEnum::ENUM],
        ];
    }

    #[DataProvider("providesNameFunctionTestcases")]
    public function testNameShouldReturnName(string $method, UnitEnum $enum): void
    {
        $this->assertEquals(
            $enum->name,
            $method($enum)
        );
    }

    public function testNameShouldAllowNull()
    {
        $this->assertNull(
            n(null)
        );

        $this->assertNull(
            name(null)
        );
    }

    public static function providesValueFunctionTestcases(): array
    {
        return [
            'v-unit' => ['Henzeb\Enumhancer\Functions\v', EnhancedUnitEnum::THIRD_ENUM],
            'v-backed' => ['Henzeb\Enumhancer\Functions\v', EnhancedBackedEnum::ENUM],
            'vl-unit' => ['Henzeb\Enumhancer\Functions\vl', EnhancedUnitEnum::THIRD_ENUM, false],
            'vl-backed' => ['Henzeb\Enumhancer\Functions\vl', EnhancedBackedEnum::ENUM, false],
            'value-unit' => ['Henzeb\Enumhancer\Functions\value', EnhancedUnitEnum::THIRD_ENUM],
            'value-backed' => ['Henzeb\Enumhancer\Functions\value', EnhancedBackedEnum::ENUM],
            'value-lower-unit' => ['Henzeb\Enumhancer\Functions\valueLowercase', EnhancedUnitEnum::THIRD_ENUM, false],
            'value-lower-backed' => ['Henzeb\Enumhancer\Functions\valueLowercase', EnhancedBackedEnum::ENUM, false],
        ];
    }
    
    #[DataProvider("providesValueFunctionTestcases")]
    public function testValueShouldReturnValue(string $method, UnitEnum $enum, bool $keepValueCase = true): void
    {
        $this->assertEquals(
            EnumValue::value($enum, $keepValueCase),
            $method($enum, $keepValueCase)
        );
    }

    public function testValueShouldAllowNull()
    {
        $this->assertNull(
            v(null)
        );

        $this->assertNull(
            vl(null)
        );

        $this->assertNull(
            EnumValue(null)
        );

        $this->assertNull(
            valueLowercase(null)
        );
    }
}
