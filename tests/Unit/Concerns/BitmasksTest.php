<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Helpers\Bitmasks\EnumBitmasks;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksCorrectModifierEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use PHPUnit\Framework\TestCase;
use TypeError;

class BitmasksTest extends TestCase
{
    protected function setUp(): void
    {
        set_error_handler(static function (int $errno, string $errstr): never {
            throw new TypeError($errstr, $errno);
        }, E_USER_ERROR);
    }

    public function testShouldReturnCorrectBit(): void
    {
        $this->assertEquals(1, IntBackedEnum::TEST->bit());
        $this->assertEquals(2, IntBackedEnum::TEST_2->bit());
        $this->assertEquals(4, IntBackedEnum::TEST_3->bit());
    }

    public function testShouldReturnBits(): void
    {
        $this->assertEquals(
            [
                1 => 'TEST',
                2 => 'TEST_2',
                4 => 'TEST_3'
            ],
            IntBackedEnum::bits()
        );
    }

    public function testShouldReturnBitmaskInstance(): void
    {
        $mask = IntBackedEnum::mask();
        $this->assertEquals(IntBackedEnum::class, $mask->forEnum());
        $this->assertEquals(0, $mask->value());
    }

    public function testShouldReturnBitmaskInstanceWithValues(): void
    {
        $mask = IntBackedEnum::mask(IntBackedEnum::TEST, 'TEST_2');
        $this->assertEquals(3, $mask->value());
    }

    public function testShouldCreateBitmaskFromMask(): void
    {
        $mask = IntBackedEnum::fromMask(3);
        $this->assertEquals(IntBackedEnum::class, $mask->forEnum());
        $this->assertEquals(3, $mask->value());

        $mask = IntBackedEnum::fromMask(4);
        $this->assertEquals(4, $mask->value());
    }

    public function testShouldTryCreateBitmaskFromMask(): void
    {
        $mask = IntBackedEnum::tryMask(3);
        $this->assertEquals(IntBackedEnum::class, $mask->forEnum());
        $this->assertEquals(3, $mask->value());

        $mask = IntBackedEnum::tryMask(4);
        $this->assertEquals(4, $mask->value());

        $mask = IntBackedEnum::tryMask(9);
        $this->assertEquals(0, $mask->value());
    }

    public function testShouldExpectMaskWithDefaultValue(): void
    {
        IntBackedEnum::setDefault(IntBackedEnum::TEST_3);
        $mask = IntBackedEnum::tryMask(9);
        $this->assertEquals(4, $mask->value());

        IntBackedEnum::setDefault(null);
    }

    public function testTryMaskShouldExpectMaskWithOverridenDefault(): void
    {
        IntBackedEnum::setDefault(IntBackedEnum::TEST_3);
        $mask = IntBackedEnum::tryMask(9, 1);
        $this->assertEquals(2, $mask->value());

        IntBackedEnum::setDefault(null);
    }

    public function testTryMaskShouldExpectMaskWithGivenDefault(): void
    {
        $mask = IntBackedEnum::tryMask(9, 1);
        $this->assertEquals(2, $mask->value());
    }

    public function testShouldUseIntegerValuesAsBit()
    {

        $this->assertEquals(
            [
                8 => 'Execute',
                16 => 'Read',
                32 => 'Write'
            ],
            BitmasksIntEnum::bits()
        );

        $this->assertEquals(16, BitmasksIntEnum::Read->bit());
    }

    public function testBitShouldThrowErrorWhenIncorrectValues()
    {
        $this->expectException(\Error::class);
        BitmasksIncorrectIntEnum::Read->bit();
    }

    public function testBitsShouldThrowErrorWhenIncorrectValues()
    {
        $this->expectException(\Error::class);
        BitmasksIncorrectIntEnum::bits();
    }

    public function testShouldThrowErrorWhenIncorrectValues()
    {
        $this->expectException(\Error::class);
        BitmasksIncorrectIntEnum::mask();
    }

    public function testFromMaskShouldThrowErrorWhenIncorrectValues()
    {
        $this->expectException(\Error::class);
        BitmasksIncorrectIntEnum::fromMask(5);
    }

    public function testTryMaskShouldThrowErrorWhenIncorrectValues()
    {
        $this->expectException(\Error::class);
        BitmasksIncorrectIntEnum::tryMask(5);
    }

    public function testShouldThrowErrorWhenCastingTobits()
    {
        $this->expectException(TypeError::class);
        EnumBitmasks::getBits(BitmasksIntEnum::class, 64);
    }

    public function testAllowAsModifier()
    {

        $this->assertSame(
            [
                1 => "Neither",
                2 => "Read",
                4 => "Write",
                6 => "Both",
            ],
            BitmasksCorrectModifierEnum::bits()
        );

        $this->assertEquals(6, BitmasksCorrectModifierEnum::mask(2, 4)->value());
    }
}
