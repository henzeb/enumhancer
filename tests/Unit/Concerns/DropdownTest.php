<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\Dropdown;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownIntLabeledEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown\DropdownStringLabeledEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Dropdown\DropdownLabeledUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Dropdown\DropdownUnitEnum;
use PHPUnit\Framework\TestCase;

class DropdownTest extends TestCase
{
    public static function dropdownTestcases(): array
    {
        return [
            [
                DropdownUnitEnum::class,
                ['orange' => 'Orange', 'apple' => 'Apple', 'banana' => 'Banana']
            ],
            [
                DropdownUnitEnum::class,
                ['Orange' => 'Orange', 'Apple' => 'Apple', 'Banana' => 'Banana'],
                true
            ],
            [
                DropdownLabeledUnitEnum::class,
                ['orange' => 'an orange', 'apple' => 'an apple', 'banana' => 'a banana']
            ],
            [
                DropdownLabeledUnitEnum::class,
                ['Orange' => 'an orange', 'Apple' => 'an apple', 'Banana' => 'a banana'],
                true
            ],
            [
                DropdownIntEnum::class,
                [2 => 'Orange', 3 => 'Apple', 5 => 'Banana']
            ],
            [
                DropdownIntLabeledEnum::class,
                [2 => 'an orange', 3 => 'an apple', 5 => 'a banana']
            ],
            [
                DropdownStringEnum::class,
                ['My orange' => 'Orange', 'My apple' => 'Apple', 'My banana' => 'Banana']
            ],
            [
                DropdownStringLabeledEnum::class,
                ['My orange' => 'an orange', 'My apple' => 'an apple', 'My banana' => 'a banana']
            ],
            [
                DropdownStringLabeledEnum::class,
                ['My orange' => 'an orange', 'My apple' => 'an apple', 'My banana' => 'a banana'],
                true
            ]
        ];
    }

    public function providesDropdownTestcases(): array
    {
        return self::dropdownTestcases();
    }

    /**
     * @param string $enum
     * @param array $expected
     * @param bool $keepCase
     * @return void
     * @dataProvider providesDropdownTestcases
     */
    public function testDropdown(string $enum, array $expected, bool $keepCase = false)
    {
        /**
         * @var $enum Dropdown
         */
        $this->assertEquals($expected, $enum::dropdown($keepCase));
    }
}
