<?php

use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;

test('set', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    expect($bitmask->set($bitmask->copy()->set(1))->value())->toBe(16);

    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    expect($bitmask->set(1)->value())->toBe(16);
    expect($bitmask->set(8)->value())->toBe(24);
    expect($bitmask->set(8)->value())->toBe(24);

    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    expect($bitmask->set(BitmasksIntEnum::Read)->value())->toBe(16);
    expect($bitmask->set(BitmasksIntEnum::Read)->value())->toBe(16);
    expect($bitmask->set(BitmasksIntEnum::Execute)->value())->toBe(24);

    $bitmask = new Bitmask(BitmasksIntEnum::class, 0);
    expect($bitmask->set('Read', 'Write')->value())->toBe(48);
});

test('unset', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
    expect($bitmask->unset($bitmask->copy()->clear()->set(1))->value())->toBe(40);

    $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
    expect($bitmask->unset(1)->value())->toBe(40);
    expect($bitmask->unset(8)->value())->toBe(32);
    expect($bitmask->unset(8)->value())->toBe(32);

    $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
    expect($bitmask->unset(BitmasksIntEnum::Read)->value())->toBe(40);
    expect($bitmask->unset(BitmasksIntEnum::Read)->value())->toBe(40);
    expect($bitmask->unset(BitmasksIntEnum::Execute)->value())->toBe(32);

    $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
    expect($bitmask->unset('Read', 'Write')->value())->toBe(8);
});

test('toggle', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 40);
    expect($bitmask->toggle('Execute')->value())->toBe(32);
    expect($bitmask->toggle(BitmasksIntEnum::Read)->value())->toBe(48);
    expect($bitmask->toggle(32, 16, 8)->value())->toBe(8);
});

test('clear', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 56);

    expect($bitmask->clear()->value())->toBe(0);
});

test('copy', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 56);
    $copy = $bitmask->copy();

    expect($bitmask !== $copy)->toBeTrue();

    expect($copy->for(BitmasksIntEnum::class))->toBeTrue();

    expect($copy->value())->toBe($bitmask->value());
});
