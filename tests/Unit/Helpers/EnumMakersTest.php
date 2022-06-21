<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use Henzeb\Enumhancer\Helpers\EnumMakers;

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

        EnumMakers::make(stdClass::class, 'test');
    }

    public function testTryMakeShouldFailWithInvalidClass()
    {
        $this->expectError();

        EnumMakers::tryMake(stdClass::class, 'test');
    }

    public function testMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectError();

        EnumMakers::makeArray(stdClass::class, ['test']);
    }

    public function testTryMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectError();

        EnumMakers::tryMakeArray(stdClass::class, ['test']);
    }

    public function testTryCastReturnsNull()
    {
        $this->assertNull(EnumMakers::tryCast(EnhancedUnitEnum::class, 'DoesnotExist'));
    }

    public function testTryCast()
    {
        $this->assertEquals(EnhancedUnitEnum::ENUM, EnumMakers::tryCast(EnhancedUnitEnum::class, 'ENUM'));
    }

    public function testTryCastAlreadyEnum()
    {
        $this->assertEquals(EnhancedUnitEnum::ENUM, EnumMakers::tryCast(EnhancedUnitEnum::class, EnhancedUnitEnum::ENUM));
    }
}
