<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use Henzeb\Enumhancer\Exceptions\NotAnEnumException;
use Henzeb\Enumhancer\Helpers\EnumBlade;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase;
use UnitEnum;
use function Henzeb\Enumhancer\Functions\backing;
use function Henzeb\Enumhancer\Functions\name;
use function Henzeb\Enumhancer\Functions\value;

class EnumBladeTest extends TestCase
{
    use InteractsWithViews;

    public static function providesTestcases(): array
    {
        return [
            'int-backed' => [IntBackedEnum::TEST],
            'string-backed' => [EnhancedBackedEnum::ENUM],
            'unit' => [EnhancedUnitEnum::ENUM],

            'int-backed-lower' => [IntBackedEnum::TEST, false],
            'string-backed-lower' => [EnhancedBackedEnum::ENUM, false],
            'unit-lower' => [EnhancedUnitEnum::ENUM, false],
        ];
    }

    /**
     * @param UnitEnum $enum
     * @param bool $keepValueCase
     * @return void
     *
     * @dataProvider providesTestcases
     */
    public function testShouldRenderValue(UnitEnum $enum, bool $keepValueCase = true): void
    {
        $method = $keepValueCase ? 'register' : 'registerLowercase';
        EnumBlade::$method($enum::class);

        $this->assertEquals(
            (string)value($enum, $keepValueCase),
            $this->blade('{{ $data }}',
                ['data' => $enum], true
            )
        );

        $this->assertEquals(
            backing($enum, $keepValueCase)->value,
            $this->blade('{{ $data }}',
                ['data' => backing($enum, $keepValueCase)], true
            )
        );

        $this->assertEquals(
            backing($enum, $keepValueCase)->value,
            $this->blade('{{ $data->value }}',
                ['data' => backing($enum, $keepValueCase)], true
            )
        );

        $this->assertEquals(
            name($enum),
            $this->blade('{{ $data->name }}',
                ['data' => $enum], true
            )
        );
    }

    public function testShouldFailAddingNonEnumLowercase(): void
    {
        $this->expectException(NotAnEnumException::class);
        EnumBlade::registerLowercase(self::class);
    }

    public function testShouldFailAddingNonEnum(): void
    {
        $this->expectException(NotAnEnumException::class);
        EnumBlade::register(self::class);
    }
}
