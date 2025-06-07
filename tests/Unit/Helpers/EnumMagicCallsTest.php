<?php

use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;

test('should throw exception when method not available', function () {
    expect(fn() => StateElevatorEnum::Open->doesNotExist())
        ->toThrow(\BadMethodCallException::class);
});

test('should throw exception when method not available static', function () {
    expect(fn() => StateElevatorEnum::doesNotExist())
        ->toThrow(\BadMethodCallException::class);
});
