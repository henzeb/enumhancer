<?php

use Henzeb\Enumhancer\Concerns\Comparison;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;

test('enum equals', function () {
    expect(EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ENUM))->toBeTrue();
});

test('enum not equals', function () {
    expect(EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ANOTHER_ENUM))->toBeFalse();
});


test('equals does not accept different object', function () {
    $class = new class {
        use Comparison;
    };

    EnhancedBackedEnum::ENUM->equals($class);
})->throws(TypeError::class);

test('when multiple values are given and one is true', function () {
    expect(EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ANOTHER_ENUM, EnhancedBackedEnum::ENUM))->toBeTrue();
});

test('when multiple values are given and none is true', function () {
    expect(EnhancedBackedEnum::ENUM->equals(EnhancedBackedEnum::ANOTHER_ENUM,
        EnhancedBackedEnum::ANOTHER_ENUM))->toBeFalse();
});

test('when string equals name', function () {
    expect(EnhancedBackedEnum::ENUM->equals('ENUM'))->toBeTrue();
});

test('when string not equals name', function () {
    expect(EnhancedBackedEnum::ENUM->equals('TEST2'))->toBeFalse();
});

test('when string equals value', function () {
    expect(EnhancedBackedEnum::ENUM->equals('an enum'))->toBeTrue();
});

test('when string equals value with capitals', function () {
    expect(EnhancedBackedEnum::WITH_CAPITALS->equals('THIRD enum'))->toBeTrue();
});

test('when string not equals value', function () {
    expect(EnhancedBackedEnum::ENUM->equals('not an enum'))->toBeFalse();
});

test('should match with unit enum value', function () {
    expect(EnhancedUnitEnum::ENUM->equals('enum'))->toBeTrue();
});

test('should match with unit enum value 2', function () {
    expect(EnhancedUnitEnum::ENUM->equals('Enum'))->toBeTrue();
});

test('should match with unit enum value without value method', function () {
    expect(SubsetUnitEnum::ENUM->equals('enum'))->toBeTrue();
});

test('should match with int backed enum value', function () {
    expect(IntBackedEnum::TEST->equals(0))->toBeTrue();
});

test('should not match with int backed enum value', function () {
    expect(IntBackedEnum::TEST->equals(1))->toBeFalse();
});

test('should return true using magic function', function () {
    expect(IntBackedEnum::TEST->isTest())->toBeTrue();
});

test('should fail using magic function that does not exist', function () {
    IntBackedEnum::TEST->isClosed();
})->throws(BadMethodCallException::class);

test('should return true using magic function is not', function () {
    expect(IntBackedEnum::TEST_2->isNotTest())->toBeTrue();
});

test('should return true using magic function with value', function () {
    expect(IntBackedEnum::TEST->is0())->toBeTrue();
});

test('should return true using magic function with value is not', function () {
    expect(IntBackedEnum::TEST_2->isNot0())->toBeTrue();
});

test('should return false using magic function', function () {
    expect(IntBackedEnum::TEST->isTest_2())->toBeFalse();
});

test('should return true using magic function basic', function () {
    expect(EnhancedUnitEnum::ENUM->isEnum())->toBeTrue();
});

test('should return true using magic function basic is not', function () {
    expect(EnhancedUnitEnum::ANOTHER_ENUM->isNotEnum())->toBeTrue();
});

test('should return false using magic function basic', function () {
    expect(EnhancedUnitEnum::ENUM->isAnother_Enum())->toBeFalse();
});

test('should return false using magic function basic is not', function () {
    expect(EnhancedUnitEnum::ENUM->isNotEnum())->toBeFalse();
});

test('should throw exception when enum not exists magic function', function () {
    EnhancedUnitEnum::ENUM->isDoesNotExist();
})->throws(BadMethodCallException::class);

test('should throw exception when method not exists magic function', function () {
    EnhancedUnitEnum::ENUM->doesNotExist();
})->throws(BadMethodCallException::class);

test('should work without issues calling self', function () {
    expect(EnhancedUnitEnum::ENUM->isEnumFunction())->toBeTrue();
});

test('passing null returns false', function () {
    expect(EnhancedUnitEnum::ENUM->equals(null))->toBeFalse();
    expect(EnhancedBackedEnum::ENUM->equals(null))->toBeFalse();
});

test('passing enums', function () {
    expect(EnhancedBackedEnum::ENUM->equals(EnhancedUnitEnum::ENUM))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isMapped())->toBeTrue();

    EnhancedBackedEnum::ANOTHER_ENUM->isExpectedToFail();
})->throws(BadMethodCallException::class);

test('is', function () {
    expect(EnhancedBackedEnum::ANOTHER_ENUM->is('another_enum'))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->is(1))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->is(EnhancedUnitEnum::ANOTHER_ENUM))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->is('mapped'))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->is('something else'))->toBeFalse();
});

test('is not', function () {
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNot('another_enum'))->toBeFalse();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNot(2))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNot(EnhancedUnitEnum::ANOTHER_ENUM))->toBeFalse();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNot('mapped'))->toBeFalse();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNot('something else'))->toBeTrue();
});

test('is in', function () {
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isIn('another_enum', 'somethingElse'))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isIn(EnhancedUnitEnum::ANOTHER_ENUM, 'somethingElse'))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isIn(0, 1))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isIn(0, 2))->toBeFalse();
});

test('is not in', function () {
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNotIn('other_enums', 'somethingElse'))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNotIn(EnhancedUnitEnum::ENUM, 'somethingElse'))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNotIn(0, 2))->toBeTrue();
    expect(EnhancedBackedEnum::ANOTHER_ENUM->isNotIn(0, 1))->toBeFalse();
});
