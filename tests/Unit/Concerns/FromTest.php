<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableNonBackedEnum;


class FromTest extends TestCase
{
    function testNonBackedEnumCanCallFrom(): void
    {
        $this->assertEquals(
            ConstructableNonBackedEnum::CALLABLE,
            ConstructableNonBackedEnum::from('callable')
        );
    }

    function testNonBackedEnumCanCallFromAndFail(): void
    {
        $this->expectError();

        ConstructableNonBackedEnum::from('doesnotexist');
    }

    function testNonBackedEnumCanCallTryFrom(): void
    {
        $this->assertEquals(
            ConstructableNonBackedEnum::CALLABLE,
            ConstructableNonBackedEnum::tryFrom('callable')
        );
    }

    function testTryFromShouldReturnNull(): void
    {
        $this->assertNull(
            ConstructableNonBackedEnum::tryFrom('doesNotExist')
        );
    }
}
