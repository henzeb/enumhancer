<?php

use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\IllegalEnumTransitionException;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\State\PostStatus;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorComplexEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorDisableTransitionEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use Mockery\Mock;

afterEach(function () {
    StateElevatorEnum::unsetAll();
    \Mockery::close();
});

test('basic transition', function () {
    expect(
        StateElevatorEnum::Open->transitionTo('close')
            ->transitionTo('Move')
            ->transitionTo(StateElevatorEnum::Stop)
    )->toBe(StateElevatorEnum::Stop);

    expect(
        StateElevatorEnum::Open->to('close')
            ->to('Move')
            ->to(StateElevatorEnum::Stop)
    )->toBe(StateElevatorEnum::Stop);
});

test('complex transition', function () {
    expect(
        StateElevatorComplexEnum::Open
            ->transitionTo('close')
            ->transitionTo('open')
            ->transitionTo('close')
            ->transitionTo('move')
            ->transitionTo('stop')
            ->transitionTo('open')
            ->transitionTo('close')
    )->toBe(StateElevatorComplexEnum::Close);
});


test('illegal transitions throw exception', function (mixed $from, mixed $to) {
    $from->transitionTo($to);
})->with([
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
])->throws(IllegalEnumTransitionException::class);

test('illegal transitions to throw exception', function (mixed $from, mixed $to) {
    $from->to($to);
})->with([
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
])->throws(IllegalEnumTransitionException::class);

test('null parameter disables transition', function () {
    expect(StateElevatorDisableTransitionEnum::Open->isTransitionAllowed('close'))->toBeFalse();

    expect(fn() => StateElevatorDisableTransitionEnum::Open->transitionTo('close'))->toThrow(IllegalEnumTransitionException::class);
});

test('null parameter disables transition with to', function () {
    expect(StateElevatorDisableTransitionEnum::Open->isTransitionAllowed('close'))->toBeFalse();

    expect(fn() => StateElevatorDisableTransitionEnum::Open->to('close'))->toThrow(IllegalEnumTransitionException::class);
});

test('close to move still works when custom transitions', function () {
    expect(StateElevatorDisableTransitionEnum::Close->isTransitionAllowed('move'))->toBeTrue();
});

test('transitions should be fully propagated when using custom transitions', function () {
    expect(StateElevatorDisableTransitionEnum::transitions())->toBe([
        'Open' => null,
        'Close' => StateElevatorDisableTransitionEnum::Move,
        'Move' => StateElevatorDisableTransitionEnum::Stop,
    ]);
});

test('transitions not allowed', function (mixed $from, mixed $to) {
    expect($from->isTransitionAllowed($to))->toBeFalse();
})->with([
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
]);

test('transition not allowed by transition hook', function () {
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
    $hook = \Mockery::mock($hook)->makePartial();
    $hook->expects('OpenClose')->never();

    expect(StateElevatorEnum::Open->isTransitionAllowed('close', $hook))->toBeFalse();
});

test('transition not allowed by stored transition hook', function () {
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
    expect(StateElevatorEnum::Open->isTransitionAllowed('close'))->toBeFalse();
});

test('runs transition hook', function () {
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

    expect(StateElevatorEnum::Open->transitionTo('close', $hook))->toBe(StateElevatorEnum::Close);
});

test('runs stored transition hook', function () {
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
    expect(StateElevatorEnum::Open->transitionTo('close'))->toBe(StateElevatorEnum::Close);
});

test('transition fails with both transition hooks', function () {
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
    expect(StateElevatorEnum::Open->isTransitionAllowed('close', $hookSuccess))->toBeFalse();

    StateElevatorEnum::setTransitionHook($hookFail);
    expect(StateElevatorEnum::Open->isTransitionAllowed('close', $hookFail))->toBeFalse();

    StateElevatorEnum::setTransitionHook($hookSuccess);
    expect(StateElevatorEnum::Open->isTransitionAllowed('close', $hookFail))->toBeFalse();

    StateElevatorEnum::setTransitionHook($hookSuccess);
    expect(StateElevatorEnum::Open->isTransitionAllowed('close', $hookSuccess))->toBeTrue();
});

test('try to', function () {
    expect(StateElevatorEnum::Open->tryTo('close'))->toBe(StateElevatorEnum::Close);
    expect(StateElevatorEnum::Open->tryTo(StateElevatorEnum::Close))->toBe(StateElevatorEnum::Close);

    expect(StateElevatorEnum::Open->tryTo('up'))->toBe(StateElevatorEnum::Open);

    expect(StateElevatorEnum::Open->tryTo('move'))->toBe(StateElevatorEnum::Open);

    expect(StateElevatorEnum::Open->tryTo(StateElevatorEnum::Move))->toBe(StateElevatorEnum::Open);
});

test('magic calls', function () {
    expect(StateElevatorEnum::Open->tryToClose())->toBe(StateElevatorEnum::Close);

    expect(StateElevatorEnum::Open->toClose())->toBe(StateElevatorEnum::Close);

    expect(StateElevatorEnum::Open->tryToMove())->toBe(StateElevatorEnum::Open);

    expect(fn() => StateElevatorEnum::Open->toMove())->toThrow(IllegalEnumTransitionException::class);
});

test('magic calls with hooks', function () {
    expect(StateElevatorEnum::Open->tryToClose())->toBe(StateElevatorEnum::Close);

    expect(StateElevatorEnum::Open->toClose())->toBe(StateElevatorEnum::Close);

    expect(StateElevatorEnum::Open->tryToMove())->toBe(StateElevatorEnum::Open);

    expect(fn() => StateElevatorEnum::Open->toMove())->toThrow(IllegalEnumTransitionException::class);
});

test('states with backed enum', function () {
    expect(PostStatus::DRAFT->to(PostStatus::READY))->toBe(PostStatus::READY);
});
