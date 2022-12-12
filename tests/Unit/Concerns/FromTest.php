<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\From\FromWithMappersEnum;
use PHPUnit\Framework\TestCase;
use ValueError;


class FromTest extends TestCase
{
    function testNonBackedEnumCanCallFrom(): void
    {
        $this->assertEquals(
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::from('callable')
        );
    }

    function testUnitEnumCanCallFromAndFail(): void
    {
        $this->expectError();

        ConstructableUnitEnum::from('doesnotexist');
    }

    function testUnitEnumCanCallTryFrom(): void
    {
        $this->assertEquals(
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::tryFrom('callable')
        );
    }

    function testTryFromShouldReturnNull(): void
    {
        $this->assertNull(
            ConstructableUnitEnum::tryFrom('doesNotExist')
        );
    }

    public function testAllowEnumAsValue()
    {
        $this->assertEquals(
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::tryFrom(ConstructableUnitEnum::CALLABLE)
        );

        $this->assertEquals(
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::from(ConstructableUnitEnum::CALLABLE)
        );
    }

    public function testAllowMappingWhenPassingEnum()
    {
        $this->assertEquals(
            FromWithMappersEnum::NotTranslated,
            FromWithMappersEnum::tryFrom(StringBackedGetEnum::Translated)
        );

        $this->assertNull(
            FromWithMappersEnum::tryFrom(StringBackedGetEnum::TEST)
        );

        $this->assertNull(
            FromWithMappersEnum::tryFrom('Translated')
        );

        $this->assertEquals(
            FromWithMappersEnum::NotTranslated,
            FromWithMappersEnum::from(StringBackedGetEnum::Translated)
        );

        $this->expectException(ValueError::class);

        FromWithMappersEnum::from('Translated');

    }
}
