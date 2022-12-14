<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Mockery;
use UnitEnum;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\IllegalEnumTransitionException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorComplexEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorDisableTransitionEnum;

class StateTest extends MockeryTestCase
{
    protected function tearDown(): void
    {
        StateElevatorEnum::unsetAll();
        parent::tearDown();
    }

    public function testBasicTransition(): void
    {
        $this->assertEquals(
            StateElevatorEnum::Stop,
            StateElevatorEnum::Open->transitionTo('close')
                ->transitionTo('Move')
                ->transitionTo(StateElevatorEnum::Stop)
        );

        $this->assertEquals(
            StateElevatorEnum::Stop,
            StateElevatorEnum::Open->to('close')
                ->to('Move')
                ->to(StateElevatorEnum::Stop)
        );
    }

    public function testComplexTransition(): void
    {
        $this->assertEquals(
            StateElevatorComplexEnum::Close,
            StateElevatorComplexEnum::Open
                ->transitionTo('close')
                ->transitionTo('open')
                ->transitionTo('close')
                ->transitionTo('move')
                ->transitionTo('stop')
                ->transitionTo('open')
                ->transitionTo('close')
        );
    }

    public function providesNotAllowedTransitionTestcases(): array
    {
        return [
            'basic-open-move' => [StateElevatorEnum::Open, 'Move'],
            'basic-open-stop' => [StateElevatorEnum::Open, 'Stop'],

            'basic-close-stop' => [StateElevatorEnum::Close, 'stop'],
            'basic-close-open' => [StateElevatorEnum::Close, 'open'],

            'basic-move-close' => [StateElevatorEnum::Move, StateElevatorEnum::Close],
            'basic-move-open' => [StateElevatorEnum::Move, StateElevatorEnum::Open],

            'basic-stop-open' => [StateElevatorEnum::Stop, 'open'],
            'basic-stop-close' => [StateElevatorEnum::Stop, 'close'],
            'basic-stop-move' => [StateElevatorEnum::Stop, 'move'],

            'complex-open-move' => [StateElevatorComplexEnum::Open, 'move'],
            'complex-open-stop' => [StateElevatorComplexEnum::Open, 'stop'],

            'complex-close-stop' => [StateElevatorComplexEnum::Close, 'stop'],

            'complex-move-open' => [StateElevatorComplexEnum::Move, 'open'],
            'complex-move-close' => [StateElevatorComplexEnum::Move, 'close'],

            'complex-stop-move' => [StateElevatorComplexEnum::Stop, 'move'],
            'complex-stop-close' => [StateElevatorComplexEnum::Stop, 'close'],
        ];
    }

    /**
     * @param StateElevatorEnum|StateElevatorComplexEnum|UnitEnum|string|int $from
     * @param StateElevatorEnum|StateElevatorComplexEnum|string|int $to
     * @return void
     *
     * @dataProvider providesNotAllowedTransitionTestcases
     */
    public function testIllegalTransitionsThrowException(mixed $from, mixed $to): void
    {
        $this->expectException(IllegalEnumTransitionException::class);

        $from->transitionTo($to);
    }

    /**
     * @param StateElevatorEnum|StateElevatorComplexEnum|UnitEnum|string|int $from
     * @param StateElevatorEnum|StateElevatorComplexEnum|string|int $to
     * @return void
     *
     * @dataProvider providesNotAllowedTransitionTestcases
     */
    public function testIllegalTransitionsToThrowException(mixed $from, mixed $to): void
    {
        $this->expectException(IllegalEnumTransitionException::class);

        $from->to($to);
    }

    public function testNullParameterDisablesTransition(): void
    {
        $this->assertFalse(StateElevatorDisableTransitionEnum::Open->isTransitionAllowed('close'));

        $this->expectException(IllegalEnumTransitionException::class);

        StateElevatorDisableTransitionEnum::Open->transitionTo('close');
    }

    public function testNullParameterDisablesTransitionWithTo(): void
    {
        $this->assertFalse(StateElevatorDisableTransitionEnum::Open->isTransitionAllowed('close'));

        $this->expectException(IllegalEnumTransitionException::class);

        StateElevatorDisableTransitionEnum::Open->to('close');
    }

    public function testCloseToMoveStillWorksWhenCustomTransitions(): void
    {
        $this->assertTrue(StateElevatorDisableTransitionEnum::Close->isTransitionAllowed('move'));
    }

    public function testTransitionsShouldBeFullyPropagatedWhenUsingCustomTransitions() {

        $this->assertEquals(
            [
                'Open'=> null,
                'Close'=> StateElevatorDisableTransitionEnum::Move,
                'Move' => StateElevatorDisableTransitionEnum::Stop,
            ],
            StateElevatorDisableTransitionEnum::transitions()
        );

    }

    /**
     * @dataProvider providesNotAllowedTransitionTestcases
     */
    public function testTransitionsNotAllowed(mixed $from, mixed $to): void
    {
        $this->assertFalse($from->isTransitionAllowed($to));
    }

    public function testTransitionNotAllowedByTransitionHook(): void
    {
        $hook = new class extends TransitionHook {

            public function OpenClose(): void
            {
            }

            public function allowsOpenClose(): bool
            {
                return false;
            }
        };

        /**
         * @var $hook TransitionHook|Mock
         */
        $hook = Mockery::mock($hook)->makePartial();
        $hook->expects('OpenClose')->never();

        $this->assertFalse(StateElevatorEnum::Open->isTransitionAllowed('close', $hook));
    }

    public function testTransitionNotAllowedByStoredTransitionHook(): void
    {
        $hook = new class extends TransitionHook {

            public function OpenClose(): void
            {
            }

            public function allowsOpenClose(): bool
            {
                return false;
            }
        };

        /**
         * @var $hook TransitionHook|Mock
         */
        $hook = Mockery::mock($hook)->makePartial();
        $hook->expects('OpenClose')->never();
        StateElevatorEnum::setTransitionHook($hook);
        $this->assertFalse(StateElevatorEnum::Open->isTransitionAllowed('close'));
    }

    public function testRunsTransitionHook(): void
    {
        $hook = new class extends TransitionHook {

            public function OpenClose(): void
            {
            }

            public function allowsOpenClose(): bool
            {
                return true;
            }
        };
        /**
         * @var $hook TransitionHook|Mock
         */
        $hook = Mockery::mock($hook)->makePartial();
        $hook->expects('OpenClose');

        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->transitionTo('close', $hook));
    }

    public function testRunsStoredTransitionHook(): void
    {
        $hook = new class extends TransitionHook {

            public function openClose(): void
            {
            }

            public function allowsOpenClose(): bool
            {
                return true;
            }
        };
        /**
         * @var $hook TransitionHook|Mock
         */
        $hook = Mockery::mock($hook)->makePartial();
        $hook->expects('openClose');
        StateElevatorEnum::setTransitionHook($hook);
        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->transitionTo('close'));
    }

    public function testTransitionFailsWithBothTransitionHooks(): void
    {
        $hookFail = new class extends TransitionHook {

            public function allowsOpenClose(): bool
            {
                return false;
            }
        };

        $hookSuccess = new class extends TransitionHook {


            public function allowsOpenClose(): bool
            {
                return true;
            }
        };

        StateElevatorEnum::setTransitionHook($hookFail);
        $this->assertFalse(StateElevatorEnum::Open->isTransitionAllowed('close', $hookSuccess));

        StateElevatorEnum::setTransitionHook($hookFail);
        $this->assertFalse(StateElevatorEnum::Open->isTransitionAllowed('close', $hookFail));

        StateElevatorEnum::setTransitionHook($hookSuccess);
        $this->assertFalse(StateElevatorEnum::Open->isTransitionAllowed('close', $hookFail));

        StateElevatorEnum::setTransitionHook($hookSuccess);
        $this->assertTrue(StateElevatorEnum::Open->isTransitionAllowed('close', $hookSuccess));
    }

    public function testTryTo(): void
    {
        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->tryTo('close'));
        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->tryTo(StateElevatorEnum::Close));

        $this->assertEquals(StateElevatorEnum::Open, StateElevatorEnum::Open->tryTo('up'));

        $this->assertEquals(StateElevatorEnum::Open, StateElevatorEnum::Open->tryTo('move'));

        $this->assertEquals(StateElevatorEnum::Open, StateElevatorEnum::Open->tryTo(StateElevatorEnum::Move));
    }

    public function testMagicCalls(): void
    {
        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->tryToClose());

        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->toClose());

        $this->assertEquals(StateElevatorEnum::Open, StateElevatorEnum::Open->tryToMove());

        $this->expectException(IllegalEnumTransitionException::class);

        StateElevatorEnum::Open->toMove();
    }

    public function testMagicCallsWithHooks(): void
    {
        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->tryToClose());

        $this->assertEquals(StateElevatorEnum::Close, StateElevatorEnum::Open->toClose());

        $this->assertEquals(StateElevatorEnum::Open, StateElevatorEnum::Open->tryToMove());

        $this->expectException(IllegalEnumTransitionException::class);

        StateElevatorEnum::Open->toMove();
    }
}
