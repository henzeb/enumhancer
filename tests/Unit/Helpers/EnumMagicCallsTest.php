<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use BadMethodCallException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use PHPUnit\Framework\TestCase;

class EnumMagicCallsTest extends TestCase
{
    public function testShouldThrowExceptionWhenMethodNotavailable(): void
    {
        $this->expectException(BadMethodCallException::class);

        StateElevatorEnum::Open->doesNotExist();
    }


    public function testShouldThrowExceptionWhenMethodNotavailableStatic(): void
    {
        $this->expectException(BadMethodCallException::class);

        StateElevatorEnum::doesNotExist();
    }
}
