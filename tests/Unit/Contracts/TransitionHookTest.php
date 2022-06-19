<?php

namespace Henzeb\Enumhancer\Tests\Unit\Contracts;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\SyntaxException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;

class TransitionHookTest extends MockeryTestCase
{
    public function testShouldExecuteAndReturnNullWithoutHook()
    {
        $mock = Mockery::mock(TransitionHook::class)->makePartial();

        $this->assertNull($mock->execute(StateElevatorEnum::Open, StateElevatorEnum::Close));
    }

    public function testShouldExecuteAndReturnNullWithHook()
    {
        $class = new class extends TransitionHook {
            public function openClose() {

            }
        };
        $mock = Mockery::mock($class)->makePartial();
        $mock->expects('openClose');
        $mock->execute(StateElevatorEnum::Open, StateElevatorEnum::Close);
    }

    public function testShouldReturnTrueWithoutHook()
    {
        $class = new class extends TransitionHook {
        };

        $this->assertTrue($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close));
    }

    public function testShouldReturnTrueWithHookReturningNothing()
    {
        $class = new class extends TransitionHook {
            public function allowedOpenClose() {

            }
        };

        $this->assertTrue($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close));
    }

    public function testShouldThrowExceptionWhenNotReturningBool()
    {
        $class = new class extends TransitionHook {
            public function allowsOpenClose() {
                return 'string';
            }
        };

        $this->expectException(SyntaxException::class);

        $this->assertTrue($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close));
    }

    public function testShouldReturnTrueWithHook()
    {
        $class = new class extends TransitionHook {
            public function allowsOpenClose() {
                return true;
            }
        };

        $this->assertTrue($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close));
    }

    public function testShouldReturnFalseWithHook()
    {
        $class = new class extends TransitionHook {
            public function allowsOpenClose() {
                return false;
            }
        };

        $this->assertFalse($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close));
    }
}
