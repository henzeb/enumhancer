<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use UnitEnum;
use Orchestra\Testbench\TestCase;
use Henzeb\Enumhancer\Helpers\EnumBlade;
use Illuminate\View\Compilers\BladeCompiler;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use function Henzeb\Enumhancer\Functions\name;
use function Henzeb\Enumhancer\Functions\value;
use function Henzeb\Enumhancer\Functions\backing;

class EnumBladeTest extends TestCase
{
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
            BladeCompiler::render('{{ $data }}',
                ['data' => $enum], true
            )
        );

        $this->assertEquals(
            backing($enum, $keepValueCase)->value,
            BladeCompiler::render('{{ $data }}',
                ['data' => backing($enum, $keepValueCase)], true
            )
        );

        $this->assertEquals(
            backing($enum, $keepValueCase)->value,
            BladeCompiler::render('{{ $data->value }}',
                ['data' => backing($enum, $keepValueCase)], true
            )
        );

        $this->assertEquals(
            name($enum),
            BladeCompiler::render('{{ $data->name }}',
                ['data' => $enum], true
            )
        );
    }
}
