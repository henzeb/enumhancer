<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use UnitEnum;
use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\DefaultsEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\DefaultsNullEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\DefaultsMappedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsNullIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\DefaultsOverriddenEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsMappedIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsNullStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsMappedStringEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsOverriddenIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\DefaultsOverriddenStringEnum;

class DefaultsTest extends TestCase
{
    public function providesCases(): array
    {
        return [
            [DefaultsEnum::class, DefaultsEnum::Default],
            [DefaultsIntEnum::class, DefaultsIntEnum::Default],
            [DefaultsStringEnum::class, DefaultsStringEnum::Default],

            [DefaultsNullEnum::class, null],
            [DefaultsNullIntEnum::class, null],
            [DefaultsNullStringEnum::class, null],

            [DefaultsOverriddenEnum::class, DefaultsOverriddenEnum::Enum],
            [DefaultsOverriddenIntEnum::class, DefaultsOverriddenIntEnum::Enum],
            [DefaultsOverriddenStringEnum::class, DefaultsOverriddenStringEnum::Enum],

            [DefaultsMappedEnum::class, DefaultsMappedEnum::Enum],
            [DefaultsMappedIntEnum::class, DefaultsMappedIntEnum::Enum],
            [DefaultsMappedStringEnum::class, DefaultsMappedStringEnum::Enum],
        ];
    }

    public function providesAssertionTestcases(): array
    {
        return [
            [DefaultsEnum::Default, true],
            [DefaultsEnum::Enum, false],
            [DefaultsIntEnum::Default, true],
            [DefaultsIntEnum::Enum, false],
            [DefaultsStringEnum::Default, true],
            [DefaultsStringEnum::Enum, false],

            [DefaultsNullEnum::Enum, false],
            [DefaultsNullIntEnum::Enum, false],
            [DefaultsNullStringEnum::Enum, false],

            [DefaultsOverriddenEnum::Enum, true],
            [DefaultsOverriddenEnum::DefaultEnum, false],
            [DefaultsOverriddenIntEnum::Enum, true],
            [DefaultsOverriddenIntEnum::Default, false],
            [DefaultsOverriddenStringEnum::Enum, true],
            [DefaultsOverriddenStringEnum::Default, false],

            [DefaultsMappedEnum::Enum, true],
            [DefaultsMappedEnum::DefaultEnum, false],
            [DefaultsMappedIntEnum::Enum, true],
            [DefaultsMappedIntEnum::DefaultEnum, false],
            [DefaultsMappedStringEnum::Enum, true],
            [DefaultsMappedStringEnum::DefaultEnum, false],
        ];
    }

    /**
     * @param string $enum
     * @param mixed $expected
     * @return void
     *
     * @dataProvider providesCases
     */
    public function testShouldReturnDefault(string $enum, mixed $expected): void
    {
        /**
         * @var $enum Defaults;
         */
        $this->assertEquals($expected, $enum::default());
    }

    /**
     * @param string $enum
     * @param mixed $expected
     * @return void
     *
     * @dataProvider providesCases
     */
    public function testShouldReturnTryMakeDefault(string $enum, mixed $expected): void
    {
        /**
         * @var $enum Makers
         */
        $this->assertEquals($expected, $enum::tryMake('default'));
    }

    public function testTryFromShouldUseDefault(): void
    {
        $this->assertEquals(DefaultsOverriddenEnum::Enum, DefaultsOverriddenEnum::tryFrom('default'));
    }

    public function testFromShouldUseDefault(): void
    {
        $this->assertEquals(DefaultsOverriddenEnum::Enum, DefaultsOverriddenEnum::from('default'));
    }

    /**
     * @param UnitEnum $enum
     * @param bool $expected
     * @return void
     *
     * @dataProvider providesAssertionTestcases
     */
    public function testIsDefault(UnitEnum $enum, bool $expected): void
    {
        /**
         * @var $enum Defaults
         */
        $this->assertEquals($expected, $enum->isDefault());
    }

    /**
     * @param UnitEnum $enum
     * @param bool $expected
     * @return void
     *
     * @dataProvider providesAssertionTestcases
     */
    public function testIsNotDefault(UnitEnum $enum, bool $expected): void
    {
        /**
         * @var $enum Defaults
         */
        $this->assertEquals(!$expected, $enum->isNotDefault());
    }
}
