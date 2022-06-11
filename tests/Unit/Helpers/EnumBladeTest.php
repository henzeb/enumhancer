<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use UnitEnum;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Blade;
use Henzeb\Enumhancer\Helpers\EnumBlade;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use function Henzeb\Enumhancer\Functions\name;
use function Henzeb\Enumhancer\Functions\value;
use function Henzeb\Enumhancer\Functions\backing;

class EnumBladeTest extends TestCase
{
    use InteractsWithViews;
    public function providesTestcases(): array
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
}
