<?php

namespace Unit\Helpers;

use Henzeb\Enumhancer\Helpers\EnumMakers;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class EnumMakersTest extends TestCase
{
    public function testMakeShouldFailWithInvalidClass()
    {
        $this->expectException(RuntimeException::class);

        EnumMakers::make(stdClass::class, 'test');
    }

    public function testTryMakeShouldFailWithInvalidClass()
    {
        $this->expectException(RuntimeException::class);

        EnumMakers::tryMake(stdClass::class, 'test');
    }

    public function testMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectException(RuntimeException::class);

        EnumMakers::makeArray(stdClass::class, ['test']);
    }

    public function testTryMakeArrayShouldFailWithInvalidClass()
    {
        $this->expectException(RuntimeException::class);

        EnumMakers::tryMakeArray(stdClass::class, ['test']);
    }
}
