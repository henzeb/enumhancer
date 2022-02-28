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
}
