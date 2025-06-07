<?php

use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\SyntaxException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;

test('should execute and return null without hook', function () {
    $mock = Mockery::mock(TransitionHook::class)->makePartial();

    expect($mock->execute(StateElevatorEnum::Open, StateElevatorEnum::Close))->toBeNull();
});

test('should execute and return null with hook', function () {
    $class = new class extends TransitionHook {
        public function openClose() {

        }
    };
    $mock = Mockery::mock($class)->makePartial();
    $mock->expects('openClose');
    $mock->execute(StateElevatorEnum::Open, StateElevatorEnum::Close);
});

test('should return true without hook', function () {
    $class = new class extends TransitionHook {
    };

    expect($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close))->toBeTrue();
});

test('should return true with hook returning nothing', function () {
    $class = new class extends TransitionHook {
        public function allowedOpenClose() {

        }
    };

    expect($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close))->toBeTrue();
});

test('should throw exception when not returning bool', function () {
    $class = new class extends TransitionHook {
        public function allowsOpenClose() {
            return 'string';
        }
    };

    $class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close);
})->throws(SyntaxException::class);

test('should return true with hook', function () {
    $class = new class extends TransitionHook {
        public function allowsOpenClose() {
            return true;
        }
    };

    expect($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close))->toBeTrue();
});

test('should return false with hook', function () {
    $class = new class extends TransitionHook {
        public function allowsOpenClose() {
            return false;
        }
    };

    expect($class->isAllowed(StateElevatorEnum::Open, StateElevatorEnum::Close))->toBeFalse();
});
