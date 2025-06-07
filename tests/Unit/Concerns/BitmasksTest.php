<?php

use Henzeb\Enumhancer\Helpers\Bitmasks\EnumBitmasks;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksCorrectModifierEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;

beforeEach(function () {
    set_error_handler(static function (int $errno, string $errstr): never {
        throw new TypeError($errstr, $errno);
    }, E_USER_ERROR);
});

afterEach(function () {
    restore_error_handler();
    restore_exception_handler();
});

test('should return correct bit', function () {
    expect(IntBackedEnum::TEST->bit())->toBe(1);
    expect(IntBackedEnum::TEST_2->bit())->toBe(2);
    expect(IntBackedEnum::TEST_3->bit())->toBe(4);
});

test('should return bits', function () {
    expect(IntBackedEnum::bits())->toBe([
        1 => 'TEST',
        2 => 'TEST_2',
        4 => 'TEST_3'
    ]);
});

test('should return bitmask instance', function () {
    $mask = IntBackedEnum::mask();
    expect($mask->forEnum())->toBe(IntBackedEnum::class);
    expect($mask->value())->toBe(0);
});

test('should return bitmask instance with values', function () {
    $mask = IntBackedEnum::mask(IntBackedEnum::TEST, 'TEST_2');
    expect($mask->value())->toBe(3);
});

test('should create bitmask from mask', function () {
    $mask = IntBackedEnum::fromMask(3);
    expect($mask->forEnum())->toBe(IntBackedEnum::class);
    expect($mask->value())->toBe(3);

    $mask = IntBackedEnum::fromMask(4);
    expect($mask->value())->toBe(4);
});

test('should try create bitmask from mask', function () {
    $mask = IntBackedEnum::tryMask(3);
    expect($mask->forEnum())->toBe(IntBackedEnum::class);
    expect($mask->value())->toBe(3);

    $mask = IntBackedEnum::tryMask(4);
    expect($mask->value())->toBe(4);

    $mask = IntBackedEnum::tryMask(9);
    expect($mask->value())->toBe(0);
});

test('should expect mask with default value', function () {
    IntBackedEnum::setDefault(IntBackedEnum::TEST_3);
    $mask = IntBackedEnum::tryMask(9);
    expect($mask->value())->toBe(4);

    IntBackedEnum::setDefault(null);
});

test('try mask should expect mask with overriden default', function () {
    IntBackedEnum::setDefault(IntBackedEnum::TEST_3);
    $mask = IntBackedEnum::tryMask(9, 1);
    expect($mask->value())->toBe(2);

    IntBackedEnum::setDefault(null);
});

test('try mask should expect mask with given default', function () {
    $mask = IntBackedEnum::tryMask(9, 1);
    expect($mask->value())->toBe(2);
});

test('should use integer values as bit', function () {
    expect(BitmasksIntEnum::bits())->toBe([
        8 => 'Execute',
        16 => 'Read',
        32 => 'Write'
    ]);

    expect(BitmasksIntEnum::Read->bit())->toBe(16);
});

test('bit should throw error when incorrect values', function () {
    BitmasksIncorrectIntEnum::Read->bit();
})->throws(TypeError::class);

test('bits should throw error when incorrect values', function () {
    BitmasksIncorrectIntEnum::bits();
})->throws(TypeError::class);

test('should throw error when incorrect values', function () {
    BitmasksIncorrectIntEnum::mask();
})->throws(TypeError::class);

test('from mask should throw error when incorrect values', function () {
    BitmasksIncorrectIntEnum::fromMask(5);
})->throws(TypeError::class);

test('try mask should throw error when incorrect values', function () {
    BitmasksIncorrectIntEnum::tryMask(5);
})->throws(TypeError::class);

test('should throw error when casting to bits', function () {
    EnumBitmasks::getBits(BitmasksIntEnum::class, 64);
})->throws(TypeError::class);

test('allow as modifier', function () {
    expect(BitmasksCorrectModifierEnum::bits())->toBe([
        1 => "Neither",
        2 => "Read",
        4 => "Write",
        6 => "Both",
    ]);

    expect(BitmasksCorrectModifierEnum::mask(2, 4)->value())->toBe(6);
});