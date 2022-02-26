<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;


class ValueTest extends TestCase
{
    public function providesEnumsForValue()
    {
        return [
            'backed-1' => [EnhancedBackedEnum::ENUM, EnhancedBackedEnum::ENUM->value],
            'backed-2' => [EnhancedBackedEnum::ANOTHER_ENUM, EnhancedBackedEnum::ANOTHER_ENUM->value],
            'unit-1' => [EnhancedUnitEnum::ENUM, strtolower(EnhancedUnitEnum::ENUM->name)],
            'unit-2' => [EnhancedUnitEnum::ANOTHER_ENUM, strtolower(EnhancedUnitEnum::ANOTHER_ENUM->name)],
        ];
    }


    /**
     * @param $enum
     * @param string $expected
     * @return void
     *
     * @dataProvider providesEnumsForValue
     */
    public function testEnumShouldReturnValue($enum, string $expected): void
    {
        $this->assertEquals(
            $expected,
            $enum->value()
        );
    }
}
