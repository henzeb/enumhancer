<?php

use Henzeb\Enumhancer\Exceptions\InvalidBitmaskEnum;
use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;

test('for returns true for matching enum class', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    
    expect($bitmask->for(BitmasksIntEnum::class))->toBeTrue();
});

test('for returns false for non-matching enum class', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    
    expect($bitmask->for(BitmasksIncorrectIntEnum::class))->toBeFalse();
});

test('forOrFail returns true for matching enum class', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    
    expect($bitmask->forOrFail(BitmasksIntEnum::class))->toBeTrue();
});

test('forOrFail throws exception for non-matching enum class', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    
    $bitmask->forOrFail(BitmasksIncorrectIntEnum::class);
})->throws(InvalidBitmaskEnum::class);

test('forEnum returns the enum class', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    
    expect($bitmask->forEnum())->toBe(BitmasksIntEnum::class);
});
