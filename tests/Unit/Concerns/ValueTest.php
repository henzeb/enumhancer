<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Value\ValueIntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Value\ValueStrictEnum;
use PHPUnit\Framework\TestCase;


class ValueTest extends TestCase
{
    public static function providesEnumsForValue(): array
    {
        return [
            'backed-1' => [EnhancedBackedEnum::ENUM, EnhancedBackedEnum::ENUM->value],
            'backed-2' => [EnhancedBackedEnum::ANOTHER_ENUM, EnhancedBackedEnum::ANOTHER_ENUM->value],
            'unit-1' => [EnhancedUnitEnum::ENUM, strtolower(EnhancedUnitEnum::ENUM->name)],
            'unit-2' => [EnhancedUnitEnum::ANOTHER_ENUM, strtolower(EnhancedUnitEnum::ANOTHER_ENUM->name)],
            'unit-3' => [ValueStrictEnum::Strict, 'Strict']
        ];
    }

    public static function providesEnumsForKey(): array
    {
        return [
            'string-backed-1' => [EnhancedBackedEnum::ENUM, 0],
            'string-backed-2' => [EnhancedBackedEnum::ANOTHER_ENUM, 1],
            'int-backed-1' => [ValueIntBackedEnum::ENUM, 64],
            'int-backed-2' => [ValueIntBackedEnum::ANOTHER_ENUM, 128],
            'unit-1' => [EnhancedUnitEnum::ENUM, 0],
            'unit-2' => [EnhancedUnitEnum::ANOTHER_ENUM, 1],
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

    /**
     * @param $enum
     * @param int $expected
     * @return void
     *
     * @dataProvider providesEnumsForKey
     */
    public function testEnumShouldReturnKey($enum, int $expected): void
    {
        $this->assertEquals(
            $expected,
            $enum->key()
        );
    }
}
