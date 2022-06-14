<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use UnitEnum;
use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Exceptions\IllegalEnumTransitionException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorComplexEnum;

class StateTest extends TestCase
{
    public function testBasicTransition(): void
    {
        $this->assertEquals(
            StateElevatorEnum::Stop,
            StateElevatorEnum::Open->transitionTo('close')
                ->transitionTo('Move')
                ->transitionTo(StateElevatorEnum::Stop)
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
     * @param UnitEnum|StateElevatorEnum|StateElevatorComplexEnum|string|int $from
     * @param StateElevatorEnum|StateElevatorComplexEnum|string|int $to
     * @return void
     *
     * @dataProvider providesNotAllowedTransitionTestcases
     */
    public function testTransitionsNotAllowed(mixed $from, mixed $to): void
    {
        $this->assertFalse($from->isTransitionAllowed($to));
    }
}
