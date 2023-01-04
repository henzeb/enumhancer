<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers\Bitmasks\Concerns;

use Henzeb\Enumhancer\Exceptions\InvalidBitmaskEnum;
use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use PHPUnit\Framework\TestCase;

class BitmaskValidatorsTest extends TestCase
{
    public function testFor() {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
        $this->assertTrue(
            $bitmask->for(BitmasksIntEnum::class)
        );

        $this->assertFalse(
            $bitmask->for(BitmasksIncorrectIntEnum::class)
        );
    }


    public function testForOrFail() {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 0);

        $this->assertTrue(
            $bitmask->forOrFail(BitmasksIntEnum::class)
        );

        $this->expectException(InvalidBitmaskEnum::class);

        $bitmask->forOrFail(BitmasksIncorrectIntEnum::class);
    }

    public function testForEnum()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 0);

        $this->assertEquals(
            BitmasksIntEnum::class,
            $bitmask->forEnum()
        );
    }
}
