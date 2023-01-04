<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers\Bitmasks\Concerns;

use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use PHPUnit\Framework\TestCase;

class BitmaskModifiersTest extends TestCase
{
    public function testSet()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
        $this->assertEquals(16, $bitmask->set($bitmask->copy()->set(1))->value());

        $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
        $this->assertEquals(16, $bitmask->set(1)->value());
        $this->assertEquals(24, $bitmask->set(8)->value());
        $this->assertEquals(24, $bitmask->set(8)->value());

        $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
        $this->assertEquals(16, $bitmask->set(BitmasksIntEnum::Read)->value());
        $this->assertEquals(16, $bitmask->set(BitmasksIntEnum::Read)->value());
        $this->assertEquals(24, $bitmask->set(BitmasksIntEnum::Execute)->value());

        $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
        $this->assertEquals(48, $bitmask->set('Read', 'Write')->value());
    }

    public function testUnset()
    {

        $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
        $this->assertEquals(40, $bitmask->unset($bitmask->copy()->clear()->set(1))->value());

        $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
        $this->assertEquals(40, $bitmask->unset(1)->value());
        $this->assertEquals(32, $bitmask->unset(8)->value());
        $this->assertEquals(32, $bitmask->unset(8)->value());

        $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
        $this->assertEquals(40, $bitmask->unset(BitmasksIntEnum::Read)->value());
        $this->assertEquals(40, $bitmask->unset(BitmasksIntEnum::Read)->value());
        $this->assertEquals(32, $bitmask->unset(BitmasksIntEnum::Execute)->value());

        $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
        $this->assertEquals(8, $bitmask->unset('Read', 'Write')->value());
    }

    public function testToggle()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 40);
        $this->assertEquals(32, $bitmask->toggle('Execute')->value());
        $this->assertEquals(48, $bitmask->toggle(BitmasksIntEnum::Read)->value());
        $this->assertEquals(8, $bitmask->toggle(32, 16, 8)->value());
    }

    public function testClear()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 56);

        $this->assertEquals(0, $bitmask->clear()->value());
    }

    public function testCopy()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
        $copy = $bitmask->copy();

        $this->assertTrue($bitmask !== $copy);

        $this->assertTrue($copy->for(BitmasksIntEnum::class));

        $this->assertEquals($bitmask->value(), $copy->value());
    }

}
