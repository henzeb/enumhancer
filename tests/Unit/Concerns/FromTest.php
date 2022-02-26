<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;


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
}
