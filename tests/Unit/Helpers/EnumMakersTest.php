<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

/**
 * @ignore
 */
class EnumMakersTest extends TestCase
{
    public function testMakeShouldFailWithInvalidClass()
    {
        $this->expectException(TypeError::class);

        EnumGetters::get(stdClass::class, 'test');
    }

    public function testTryMakeShouldFailWithInvalidClass()
    {
        $this->expectException(TypeError::class);

        EnumGetters::tryGet(stdClass::class, 'test');
    }

    public function testMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectException(TypeError::class);

        EnumGetters::getArray(stdClass::class, ['test']);
    }

    public function testTryMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectException(TypeError::class);

        EnumGetters::tryArray(stdClass::class, ['test']);
    }

    public function testTryCastReturnsNull()
    {
        $this->assertNull(EnumGetters::tryCast(EnhancedUnitEnum::class, 'DoesnotExist'));
    }

    public function testTryCast()
    {
        $this->assertEquals(EnhancedUnitEnum::ENUM, EnumGetters::tryCast(EnhancedUnitEnum::class, 'ENUM'));
    }

    public function testTryCastAlreadyEnum()
    {
        $this->assertEquals(EnhancedUnitEnum::ENUM,
            EnumGetters::tryCast(EnhancedUnitEnum::class, EnhancedUnitEnum::ENUM));
    }
}
