<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use Henzeb\Enumhancer\Helpers\EnumGetters;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;

/**
 * @ignore
 */
class EnumMakersTest extends TestCase
{
    public function testMakeShouldFailWithInvalidClass()
    {
        $this->expectError();

        EnumGetters::get(stdClass::class, 'test');
    }

    public function testTryMakeShouldFailWithInvalidClass()
    {
        $this->expectError();

        EnumGetters::tryGet(stdClass::class, 'test');
    }

    public function testMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectError();

        EnumGetters::getArray(stdClass::class, ['test']);
    }

    public function testTryMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectError();

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
        $this->assertEquals(EnhancedUnitEnum::ENUM, EnumGetters::tryCast(EnhancedUnitEnum::class, EnhancedUnitEnum::ENUM));
    }
}
