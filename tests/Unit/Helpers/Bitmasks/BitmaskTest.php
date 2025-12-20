<?php

use Henzeb\Enumhancer\Exceptions\InvalidBitmaskEnum;
use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
test('should fail with string for enum', function () {
    new Bitmask('test', 1);
})->throws(\TypeError::class);

test('should fail with non enum', function () {
    new Bitmask(Bitmask::class, 1);
})->throws(TypeError::class);

test('should fail with invalid bitmask enum', function () {
    new Bitmask(BitmasksIncorrectIntEnum::class, 1);
})->throws(TypeError::class);

test('should fail with invalid bitmask value', function () {
    new Bitmask(BitmasksIntEnum::class, 7);
})->throws(InvalidBitmaskEnum::class);

test('should be without errors', function () {
    new Bitmask(BitmasksIntEnum::class, 8);
    new Bitmask(BitmasksIntEnum::class, 16);
    new Bitmask(BitmasksIntEnum::class, 24);
    new Bitmask(BitmasksIntEnum::class, 32);
    new Bitmask(BitmasksIntEnum::class, 40);

    expect(fn() => new Bitmask(BitmasksIntEnum::class, 64))->toThrow(InvalidBitmaskEnum::class);
});

test('has', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

    expect($bitmask->has(8))->toBeTrue();
    expect($bitmask->has(32))->toBeFalse();

    expect($bitmask->has(BitmasksIntEnum::Execute))->toBeTrue();
    expect($bitmask->has(BitmasksIntEnum::Write))->toBeFalse();

    expect($bitmask->has('Execute'))->toBeTrue();
    expect($bitmask->has('Write'))->toBeFalse();
});

test('all', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

    expect($bitmask->all(new Bitmask(BitmasksIntEnum::class, 0)))->toBeTrue();
    expect($bitmask->all(new Bitmask(BitmasksIntEnum::class, 24)))->toBeTrue();
    expect($bitmask->all(new Bitmask(BitmasksIntEnum::class, 32)))->toBeFalse();
    expect($bitmask->all(new Bitmask(BitmasksIntEnum::class, 40)))->toBeFalse();
    expect($bitmask->all(new Bitmask(BitmasksIntEnum::class, 56)))->toBeFalse();

    expect($bitmask->all())->toBeTrue();
    expect($bitmask->all(8, 16))->toBeTrue();
    expect($bitmask->all(32))->toBeFalse();
    expect($bitmask->all(8, 32))->toBeFalse();
    expect($bitmask->all(8, 16, 32))->toBeFalse();

    expect($bitmask->all('Execute', 'Read'))->toBeTrue();
    expect($bitmask->all('Write'))->toBeFalse();
    expect($bitmask->all('Execute', 'Write'))->toBeFalse();
    expect($bitmask->all('Execute', 'Read', 'Write'))->toBeFalse();

    expect($bitmask->all(BitmasksIntEnum::Execute, BitmasksIntEnum::Read))->toBeTrue();
    expect($bitmask->all(BitmasksIntEnum::Write))->toBeFalse();
    expect($bitmask->all(BitmasksIntEnum::Execute, BitmasksIntEnum::Write))->toBeFalse();
    expect($bitmask->all(BitmasksIntEnum::Execute, BitmasksIntEnum::Read, BitmasksIntEnum::Write))->toBeFalse();

    expect(fn() => $bitmask->all(new Bitmask(EnhancedUnitEnum::class, 1)))->toThrow(InvalidBitmaskEnum::class);
});

test('any', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

    expect($bitmask->any(new Bitmask(BitmasksIntEnum::class, 0)))->toBeTrue();
    expect($bitmask->any(new Bitmask(BitmasksIntEnum::class, 24)))->toBeTrue();
    expect($bitmask->any(new Bitmask(BitmasksIntEnum::class, 32)))->toBeFalse();
    expect($bitmask->any(new Bitmask(BitmasksIntEnum::class, 40)))->toBeFalse();
    expect($bitmask->any(new Bitmask(BitmasksIntEnum::class, 56)))->toBeFalse();

    expect($bitmask->any())->toBeTrue();
    expect($bitmask->any(8, 16))->toBeTrue();
    expect($bitmask->any(32))->toBeFalse();
    expect($bitmask->any(8, 32))->toBeTrue();
    expect($bitmask->any(8, 16, 32))->toBeTrue();

    expect($bitmask->any('Execute', 'Read'))->toBeTrue();
    expect($bitmask->any('Write'))->toBeFalse();
    expect($bitmask->any('Execute', 'Write'))->toBeTrue();
    expect($bitmask->any('Execute', 'Read', 'Write'))->toBeTrue();

    expect($bitmask->any(BitmasksIntEnum::Execute, BitmasksIntEnum::Read))->toBeTrue();
    expect($bitmask->any(BitmasksIntEnum::Write))->toBeFalse();
    expect($bitmask->any(BitmasksIntEnum::Execute, BitmasksIntEnum::Write))->toBeTrue();
    expect($bitmask->any(BitmasksIntEnum::Execute, BitmasksIntEnum::Read, BitmasksIntEnum::Write))->toBeTrue();
});

test('empty', function () {
    expect((new Bitmask(BitmasksIntEnum::class, 0)->empty())->toBeTrue();
    expect((new Bitmask(BitmasksIntEnum::class, 24)->empty())->toBeFalse();
});

test('xor', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 24);

    expect($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 0)))->toBeTrue();
    expect($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 24)))->toBeTrue();
    expect($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 32)))->toBeFalse();
    expect($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 40)))->toBeFalse();
    expect(
        $bitmask->xor(
            new Bitmask(BitmasksIntEnum::class, 40),
            new Bitmask(BitmasksIntEnum::class, 24)
        )
    )->toBeTrue();
    expect($bitmask->xor(new Bitmask(BitmasksIntEnum::class, 56)))->toBeFalse();

    expect($bitmask->xor())->toBeFalse();

    expect($bitmask->xor(8, 16))->toBeFalse();
    expect($bitmask->xor(32))->toBeFalse();
    expect($bitmask->xor(8, 32))->toBeTrue();
    expect($bitmask->xor(8, 16, 32))->toBeFalse();

    expect($bitmask->xor('Execute', 'Read'))->toBeFalse();
    expect($bitmask->xor('Write'))->toBeFalse();
    expect($bitmask->xor('Execute', 'Write'))->toBeTrue();
    expect($bitmask->xor('Execute', 'Read', 'Write'))->toBeFalse();

    expect($bitmask->xor(BitmasksIntEnum::Execute, BitmasksIntEnum::Read))->toBeFalse();
    expect($bitmask->xor(BitmasksIntEnum::Write))->toBeFalse();
    expect($bitmask->xor(BitmasksIntEnum::Execute, BitmasksIntEnum::Write))->toBeTrue();
    expect($bitmask->xor(BitmasksIntEnum::Execute, BitmasksIntEnum::Read, BitmasksIntEnum::Write))->toBeFalse();
});

test('none', function () {
    $bitmask = new Bitmask(BitmasksIntEnum::class, 8);

    expect($bitmask->none(new Bitmask(BitmasksIntEnum::class, 0)))->toBeTrue();
    expect($bitmask->none(new Bitmask(BitmasksIntEnum::class, 8)))->toBeFalse();
    expect($bitmask->none(
        new Bitmask(BitmasksIntEnum::class, 16),
        new Bitmask(BitmasksIntEnum::class, 32)
    ))->toBeTrue();
    expect($bitmask->none(
        new Bitmask(BitmasksIntEnum::class, 8),
        new Bitmask(BitmasksIntEnum::class, 32)
    ))->toBeFalse();

    expect($bitmask->none(8))->toBeFalse();
    expect($bitmask->none(32))->toBeTrue();
    expect($bitmask->none(16, 32))->toBeTrue();
    expect($bitmask->none(8, 32))->toBeFalse();

    expect($bitmask->none('Execute'))->toBeFalse();
    expect($bitmask->none('Write'))->toBeTrue();
    expect($bitmask->none('Read', 'Write'))->toBeTrue();
    expect($bitmask->none('Execute', 'Read'))->toBeFalse();

    expect($bitmask->none('Execute'))->toBeFalse();
    expect($bitmask->none('Write'))->toBeTrue();
    expect($bitmask->none('Read', 'Write'))->toBeTrue();
    expect($bitmask->none('Execute', 'Read'))->toBeFalse();

    $bitmask = new Bitmask(BitmasksIntEnum::class, 40);

    expect(
        $bitmask->none(
            new Bitmask(BitmasksIntEnum::class, 24),
        )
    )->toBeTrue();
});

test('cases', function () {
    expect((new Bitmask(BitmasksIntEnum::class, 0))->cases())->toBe([]);

    expect((new Bitmask(BitmasksIntEnum::class, 8))->cases())->toBe([
        BitmasksIntEnum::Execute
    ]);

    expect((new Bitmask(BitmasksIntEnum::class, 24))->cases())->toBe([
        BitmasksIntEnum::Execute,
        BitmasksIntEnum::Read
    ]);

    expect((new Bitmask(BitmasksIntEnum::class, 56))->cases())->toBe(BitmasksIntEnum::cases());
});
