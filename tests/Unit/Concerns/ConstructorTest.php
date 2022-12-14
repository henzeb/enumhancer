<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Tests\Fixtures\IntBackedStaticCallableEnum;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedStaticCallableEnum;
use PHPUnit\Framework\TestCase;


class ConstructorTest extends TestCase
{
    public function testShouldGetEnumUsingStaticCall(): void
    {
        $this->assertEquals(
            ConstructableUnitEnum::CALLABLE,
            ConstructableUnitEnum::CALLABLE()
        );
    }

    public function testShouldFailUsingStaticCallToUnknownEnum(): void
    {
        $this->expectException(\BadMethodCallException::class);
        ConstructableUnitEnum::CANNOT_CALL();
    }

    public function testShouldGetStringBackedEnumByName(): void
    {
        $this->assertEquals(
            StringBackedStaticCallableEnum::CALLABLE,
            StringBackedStaticCallableEnum::CALLABLE()
        );
    }

    public function testShouldGetStringBackedEnumByValue(): void
    {
        $this->assertEquals(
            StringBackedStaticCallableEnum::CALLABLE,
            StringBackedStaticCallableEnum::gets_callable()
        );
    }

    public function testShouldGetIntBackedEnumByValue(): void
    {
        $method = '0';
        $this->assertEquals(
            IntBackedStaticCallableEnum::CALLABLE,
            IntBackedStaticCallableEnum::$method()
        );
    }

}
