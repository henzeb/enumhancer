<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers\Bitmasks;

use Henzeb\Enumhancer\Exceptions\InvalidBitmaskEnum;
use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use PHPUnit\Framework\TestCase;
use TypeError;

class BitmaskTest extends TestCase
{
    public function testShouldFailWithStringForEnum(): void
    {
        $this->expectException(TypeError::class);
        new Bitmask('test', 1);
    }

    public function testShouldFailWithNonEnum(): void
    {
        $this->expectException(TypeError::class);
        new Bitmask(Bitmask::class, 1);
    }

    public function testShouldFailWithInvalidBitmaskEnum(): void
    {
        $this->expectException(TypeError::class);
        new Bitmask(BitmasksIncorrectIntEnum::class, 1);
    }

    public function testShouldFailWithInvalidBitmaskValue(): void
    {
        $this->expectException(InvalidBitmaskEnum::class);
        new Bitmask(BitmasksIntEnum::class, 7);
    }

    public function testShouldBewithoutErrors(): void
    {
        new Bitmask(BitmasksIntEnum::class, 8);
        new Bitmask(BitmasksIntEnum::class, 16);
        new Bitmask(BitmasksIntEnum::class, 24);
        new Bitmask(BitmasksIntEnum::class, 32);
        new Bitmask(BitmasksIntEnum::class, 40);

        $this->expectException(InvalidBitmaskEnum::class);
        new Bitmask(BitmasksIntEnum::class, 64);

    }

    public function testHas()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

        $this->assertTrue($bitmask->has(8));
        $this->assertFalse($bitmask->has(32));

        $this->assertTrue($bitmask->has(BitmasksIntEnum::Execute));
        $this->assertFalse($bitmask->has(BitmasksIntEnum::Write));

        $this->assertTrue($bitmask->has('Execute'));
        $this->assertFalse($bitmask->has('Write'));
    }

    public function testAll()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

        $this->assertTrue($bitmask->all(new Bitmask(BitmasksIntEnum::class, 0)));
        $this->assertTrue($bitmask->all(new Bitmask(BitmasksIntEnum::class, 24)));
        $this->assertFalse($bitmask->all(new Bitmask(BitmasksIntEnum::class, 32)));
        $this->assertFalse($bitmask->all(new Bitmask(BitmasksIntEnum::class, 40)));
        $this->assertFalse($bitmask->all(new Bitmask(BitmasksIntEnum::class, 56)));

        $this->assertTrue($bitmask->all());
        $this->assertTrue($bitmask->all(8, 16));
        $this->assertFalse($bitmask->all(32));
        $this->assertFalse($bitmask->all(8, 32));
        $this->assertFalse($bitmask->all(8, 16, 32));

        $this->assertTrue($bitmask->all('Execute', 'Read'));
        $this->assertFalse($bitmask->all('Write'));
        $this->assertFalse($bitmask->all('Execute', 'Write'));
        $this->assertFalse($bitmask->all('Execute', 'Read', 'Write'));

        $this->assertTrue($bitmask->all(BitmasksIntEnum::Execute, BitmasksIntEnum::Read));
        $this->assertFalse($bitmask->all(BitmasksIntEnum::Write));
        $this->assertFalse($bitmask->all(BitmasksIntEnum::Execute, BitmasksIntEnum::Write));
        $this->assertFalse($bitmask->all(BitmasksIntEnum::Execute, BitmasksIntEnum::Read, BitmasksIntEnum::Write));

        $this->expectException(InvalidBitmaskEnum::class);
        $bitmask->all(new Bitmask(EnhancedUnitEnum::class, 1));
    }

    public function testAny()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

        $this->assertTrue($bitmask->any(new Bitmask(BitmasksIntEnum::class, 0)));
        $this->assertTrue($bitmask->any(new Bitmask(BitmasksIntEnum::class, 24)));
        $this->assertFalse($bitmask->any(new Bitmask(BitmasksIntEnum::class, 32)));
        $this->assertFalse($bitmask->any(new Bitmask(BitmasksIntEnum::class, 40)));
        $this->assertFalse($bitmask->any(new Bitmask(BitmasksIntEnum::class, 56)));

        $this->assertTrue($bitmask->any());
        $this->assertTrue($bitmask->any(8, 16));
        $this->assertFalse($bitmask->any(32));
        $this->assertTrue($bitmask->any(8, 32));
        $this->assertTrue($bitmask->any(8, 16, 32));

        $this->assertTrue($bitmask->any('Execute', 'Read'));
        $this->assertFalse($bitmask->any('Write'));
        $this->assertTrue($bitmask->any('Execute', 'Write'));
        $this->assertTrue($bitmask->any('Execute', 'Read', 'Write'));

        $this->assertTrue($bitmask->any(BitmasksIntEnum::Execute, BitmasksIntEnum::Read));
        $this->assertFalse($bitmask->any(BitmasksIntEnum::Write));
        $this->assertTrue($bitmask->any(BitmasksIntEnum::Execute, BitmasksIntEnum::Write));
        $this->assertTrue($bitmask->any(BitmasksIntEnum::Execute, BitmasksIntEnum::Read, BitmasksIntEnum::Write));
    }

    public function testXor()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

        $this->assertTrue($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 0)));
        $this->assertTrue($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 24)));
        $this->assertFalse($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 32)));
        $this->assertFalse($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 40)));
        $this->assertTrue(
            $bitmask->xor(
                new Bitmask(BitmasksIntEnum::class, 40),
                new Bitmask(BitmasksIntEnum::class, 24)
            )
        );
        $this->assertFalse($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 56)));

        $this->assertFalse($bitmask->xor());

        $this->assertFalse($bitmask->xor(8, 16));
        $this->assertFalse($bitmask->xor(32));
        $this->assertTrue($bitmask->xor(8, 32));
        $this->assertFalse($bitmask->xor(8, 16, 32));

        $this->assertFalse($bitmask->xor('Execute', 'Read'));
        $this->assertFalse($bitmask->xor('Write'));
        $this->assertTrue($bitmask->xor('Execute', 'Write'));
        $this->assertFalse($bitmask->xor('Execute', 'Read', 'Write'));

        $this->assertFalse($bitmask->xor(BitmasksIntEnum::Execute, BitmasksIntEnum::Read));
        $this->assertFalse($bitmask->xor(BitmasksIntEnum::Write));
        $this->assertTrue($bitmask->xor(BitmasksIntEnum::Execute, BitmasksIntEnum::Write));
        $this->assertFalse($bitmask->xor(BitmasksIntEnum::Execute, BitmasksIntEnum::Read, BitmasksIntEnum::Write));
    }

    public function testNone()
    {
        $bitmask = new Bitmask(BitmasksIntEnum::class, 8);

        $this->assertTrue($bitmask->none(new Bitmask(BitmasksIntEnum::class, 0)));
        $this->assertFalse($bitmask->none(new Bitmask(BitmasksIntEnum::class, 8)));
        $this->assertTrue($bitmask->none(
            new Bitmask(BitmasksIntEnum::class, 16),
            new Bitmask(BitmasksIntEnum::class, 32)
        ));
        $this->assertFalse($bitmask->none(
            new Bitmask(BitmasksIntEnum::class, 8),
            new Bitmask(BitmasksIntEnum::class, 32)
        ));

        $this->assertFalse($bitmask->none(8));
        $this->assertTrue($bitmask->none(32));
        $this->assertTrue($bitmask->none(16, 32));
        $this->assertFalse($bitmask->none(8, 32));

        $this->assertFalse($bitmask->none('Execute'));
        $this->assertTrue($bitmask->none('Write'));
        $this->assertTrue($bitmask->none('Read', 'Write'));
        $this->assertFalse($bitmask->none('Execute', 'Read'));

        $this->assertFalse($bitmask->none('Execute'));
        $this->assertTrue($bitmask->none('Write'));
        $this->assertTrue($bitmask->none('Read', 'Write'));
        $this->assertFalse($bitmask->none('Execute', 'Read'));

        $bitmask = new Bitmask(BitmasksIntEnum::class, 40);

        $this->assertTrue(
            $bitmask->none(
                new Bitmask(BitmasksIntEnum::class, 24),
            )
        );
    }

    public function testCases()
    {
        $this->assertEquals(
            [],
            (new Bitmask(BitmasksIntEnum::class, 0))->cases()
        );

        $this->assertEquals(
            [
                BitmasksIntEnum::Execute
            ],
            (new Bitmask(BitmasksIntEnum::class, 8))->cases()
        );

        $this->assertEquals(
            [
                BitmasksIntEnum::Execute,
                BitmasksIntEnum::Read
            ],
            (new Bitmask(BitmasksIntEnum::class, 24))->cases()
        );

        $this->assertEquals(
            BitmasksIntEnum::cases(),
            (new Bitmask(BitmasksIntEnum::class, 56))->cases()
        );
    }
}
