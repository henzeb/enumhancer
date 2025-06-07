<?php

use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
test('of subset should equals', function () {
    expect(EnhancedBackedEnum::of(
        EnhancedBackedEnum::ENUM,
        EnhancedBackedEnum::ANOTHER_ENUM
    )->equals(
        EnhancedBackedEnum::ANOTHER_ENUM
    ))->toBeTrue();
});

test('of subset should not equals', function () {
    expect(EnhancedBackedEnum::of(
        EnhancedBackedEnum::ENUM,
        EnhancedBackedEnum::ANOTHER_ENUM
    )->equals(
        EnhancedBackedEnum::ENUM_3
    ))->toBeFalse();
});

test('of subset should use all cases', function () {
    expect(EnhancedBackedEnum::of()->equals(
        EnhancedBackedEnum::ANOTHER_ENUM
    ))->toBeTrue();
});

test('of subset should use all cases equals string', function () {
    expect(EnhancedBackedEnum::of()->equals(
        'ANOTHER_ENUM'
    ))->toBeTrue();
});

test('of subset should use all cases not equals string', function () {
    expect(EnhancedBackedEnum::of()->equals(
        'DOESNOTEXIST'
    ))->toBeFalse();
});

test('without all', function () {
    expect(EnhancedBackedEnum::without(
        ...EnhancedBackedEnum::cases()
    )->cases())->toBe([]);
});

test('without single case', function () {
    expect(EnhancedBackedEnum::without(EnhancedBackedEnum::WITH_CAPITALS)->cases())->toBe([
        EnhancedBackedEnum::ENUM,
        EnhancedBackedEnum::ANOTHER_ENUM,
        EnhancedBackedEnum::ENUM_3
    ]);
});

test('without multiple case', function () {
    expect(EnhancedBackedEnum::without(
        EnhancedBackedEnum::ANOTHER_ENUM,
        EnhancedBackedEnum::ENUM,
    )->cases())->toBe([
        EnhancedBackedEnum::ENUM_3,
        EnhancedBackedEnum::WITH_CAPITALS,
    ]);
});
