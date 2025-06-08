<?php

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\Mappers\EnumMapper;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers\ConstantInvalidMapperEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers\ConstantMapperClassEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers\ConstantMapperClassFlippedEnum;

function getMapper()
{
    return new class extends Mapper {

        public function mappable(): array
        {
            return [
                'mappedEnum' => EnhancedBackedEnum::ENUM
            ];
        }
    };
}

test('get should work without mapper', function () {
    expect(EnhancedBackedEnum::get('ENUM'))->toBe(EnhancedBackedEnum::ENUM);
});

test('get should error without mapper', function () {
    EnhancedBackedEnum::get('mappedEnum');
})->throws(\ValueError::class);

test('get should map', function () {
    expect(EnhancedBackedEnum::get('mappedEnum', getMapper()))->toBe(EnhancedBackedEnum::ENUM);
});

test('get should map with string map', function () {
    expect(EnhancedBackedEnum::get('mappedEnum', getMapper()::class))->toBe(EnhancedBackedEnum::ENUM);
});

test('get should throw exception for non map', function () {
    EnhancedBackedEnum::get('mappedEnum', \stdClass::class);
})->throws(\RuntimeException::class);

test('get should not map when null', function () {
    EnhancedBackedEnum::get(null, getMapper());
})->throws(\ValueError::class);

test('get should map without mapper given', function () {
    expect(EnhancedBackedEnum::get('anotherMappedEnum'))->toBe(EnhancedBackedEnum::ENUM);
});

test('get should error with map', function () {
    EnhancedBackedEnum::get('not existing', getMapper());
})->throws(\ValueError::class);

test('try get should work without mapper', function () {
    expect(EnhancedBackedEnum::tryGet('ENUM'))->toBe(EnhancedBackedEnum::ENUM);
});

test('try get should return null without mapper', function () {
    expect(EnhancedBackedEnum::tryGet('mappedEnum'))->toBeNull();
});

test('try get should not map when null', function () {
    expect(EnhancedBackedEnum::tryGet(null, getMapper()))->toBeNull();
});

test('try get should map', function () {
    expect(EnhancedBackedEnum::tryGet('mappedEnum', getMapper()))->toBe(EnhancedBackedEnum::ENUM);
});

test('try get should map without mapper given', function () {
    expect(EnhancedBackedEnum::tryGet('anotherMappedEnum'))->toBe(EnhancedBackedEnum::ENUM);
});

test('try get should return null with map', function () {
    expect(EnhancedBackedEnum::tryGet('not existing', getMapper()))->toBeNull();
});

test('get array should not map when null', function () {
    EnhancedBackedEnum::getArray([null], getMapper());
})->throws(\ValueError::class);

test('get array should work without mapper', function () {
    expect(EnhancedBackedEnum::getArray(['ENUM']))->toBe([EnhancedBackedEnum::ENUM]);
});

test('get array should throw error work without mapper', function () {
    EnhancedBackedEnum::getArray(['Does Not exist']);
})->throws(\ValueError::class);

test('get array should work wit mapper', function () {
    expect(EnhancedBackedEnum::tryArray(['mappedEnum'], getMapper()))->toBe([EnhancedBackedEnum::ENUM]);
});

test('get array should map without mapper given', function () {
    expect(EnhancedBackedEnum::GetArray(['anotherMappedEnum']))->toBe([EnhancedBackedEnum::ENUM]);
});

test('get array should throw error wit mapper', function () {
    EnhancedBackedEnum::getArray(['ENUM', 'doesNotExist'], getMapper());
})->throws(\ValueError::class);

test('try get array should work without mapper', function () {
    expect(EnhancedBackedEnum::tryArray(['ENUM', 'DoesNotExist']))->toBe([EnhancedBackedEnum::ENUM]);
});

test('try get array should not map when null', function () {
    expect(EnhancedBackedEnum::tryArray([null], getMapper()))->toBe([]);
});

test('try get array should work wit mapper', function () {
    expect(EnhancedBackedEnum::tryArray(['mappedEnum', 'DoesNotExist'], getMapper()))->toBe([EnhancedBackedEnum::ENUM]);
});

test('try array should map without mapper given', function () {
    expect(EnhancedBackedEnum::tryArray(['anotherMappedEnum']))->toBe([EnhancedBackedEnum::ENUM]);
});

test('should use mapper when constructor is used', function () {
    expect(EnhancedBackedEnum::anotherMappedEnum())->toBe(EnhancedBackedEnum::ENUM);
});

test('should extract with default mapped key', function () {
    expect(EnhancedBackedEnum::extract('This text contains anotherMappedEnum for you'))->toBe([EnhancedBackedEnum::ENUM]);
});

test('should extract with mapped key', function () {
    expect(EnhancedBackedEnum::extract('This text contains mappedEnum for you', getMapper()))->toBe([EnhancedBackedEnum::ENUM]);
});

test('should extract with mapped key and default mapped key', function () {
    expect(EnhancedBackedEnum::extract(
        'This text contains mappedEnum and anotherMappedEnum for you',
        getMapper()
    ))->toBe([EnhancedBackedEnum::ENUM, EnhancedBackedEnum::ENUM]);
});

test('should accept enums as value', function () {
    expect(EnhancedBackedEnum::tryGet(EnhancedBackedEnum::ENUM))->toBe(EnhancedBackedEnum::ENUM);

    expect(EnhancedBackedEnum::tryGet(EnhancedUnitEnum::Mapped))->toBe(EnhancedBackedEnum::ANOTHER_ENUM);

    expect(EnhancedBackedEnum::tryGet(EnhancedUnitEnum::ENUM))->toBe(EnhancedBackedEnum::ENUM);

    expect(EnhancedBackedEnum::tryGet(EnhancedUnitEnum::Unique))->toBeNull();

    expect(EnhancedBackedEnum::get(EnhancedBackedEnum::ENUM))->toBe(EnhancedBackedEnum::ENUM);

    expect(EnhancedBackedEnum::get(EnhancedUnitEnum::Mapped))->toBe(EnhancedBackedEnum::ANOTHER_ENUM);

    expect(EnhancedBackedEnum::get(EnhancedUnitEnum::ENUM))->toBe(EnhancedBackedEnum::ENUM);
});

test('should accept enums as value but throw exception for unique', function () {
    EnhancedBackedEnum::get(EnhancedUnitEnum::Unique);
})->throws(\ValueError::class);

test('should accept enums as value arrays', function () {
    expect(EnhancedBackedEnum::tryArray([EnhancedBackedEnum::ENUM]))->toBe([EnhancedBackedEnum::ENUM]);

    expect(EnhancedBackedEnum::tryArray([EnhancedUnitEnum::Mapped]))->toBe([EnhancedBackedEnum::ANOTHER_ENUM]);

    expect(EnhancedBackedEnum::tryArray([EnhancedUnitEnum::ENUM]))->toBe([EnhancedBackedEnum::ENUM]);

    expect(EnhancedBackedEnum::tryArray([EnhancedUnitEnum::Unique]))->toBe([]);

    expect(EnhancedBackedEnum::tryArray([EnhancedBackedEnum::ENUM]))->toBe([EnhancedBackedEnum::ENUM]);

    expect(EnhancedBackedEnum::getArray([EnhancedUnitEnum::ENUM]))->toBe([EnhancedBackedEnum::ENUM]);

    expect(EnhancedBackedEnum::getArray([EnhancedUnitEnum::Mapped]))->toBe([EnhancedBackedEnum::ANOTHER_ENUM]);
});

test('should accept enums as value arrays but throw exception for unique', function () {
    EnhancedBackedEnum::getArray([EnhancedUnitEnum::Unique]);
})->throws(\ValueError::class);

test('map with passed array', function () {
    expect(EnhancedBackedEnum::get('passedByArray', ['passedByArray' => EnhancedBackedEnum::ENUM]))->toBe(EnhancedBackedEnum::ENUM);

    expect(EnhancedBackedEnum::getArray(['passedByArray'], ['passedByArray' => EnhancedBackedEnum::ENUM]))->toBe([EnhancedBackedEnum::ENUM]);
});

test('map with constants', function () {
    expect(EnhancedBackedEnum::get('ConstantEnum'))->toBe(EnhancedBackedEnum::ENUM_3);

    expect(EnhancedBackedEnum::getArray(['ConstantEnum']))->toBe([EnhancedBackedEnum::ENUM_3]);
});

test('map with constants as array', function () {
    expect(EnhancedBackedEnum::get('expected'))->toBe(EnhancedBackedEnum::WITH_CAPITALS);

    expect(EnhancedBackedEnum::getArray(['expected']))->toBe([EnhancedBackedEnum::WITH_CAPITALS]);

    expect(EnhancedBackedEnum::get('expected2'))->toBe(EnhancedBackedEnum::ENUM_3);

    expect(EnhancedBackedEnum::getArray(['expected2']))->toBe([EnhancedBackedEnum::ENUM_3]);
});

test('should map with fcqn', function () {
    expect(ConstantMapperClassEnum::get('alpha'))->toBe(ConstantMapperClassEnum::Beta);

    expect(ConstantMapperClassFlippedEnum::get('beta'))->toBe(ConstantMapperClassFlippedEnum::Alpha);
});

test('should be invalid when string is class', function () {
    ConstantInvalidMapperEnum::get('Alpha');
})->throws(\ValueError::class);

test('is valid mapper', function () {
    expect(EnumMapper::isValidMapper(ConstantMapperClassEnum::class, ConstantMapperClassEnum::Beta))->toBeTrue();
    expect(EnumMapper::isValidMapper(
        ConstantMapperClassEnum::class,
        ['test' => ConstantMapperClassEnum::Alpha])
    )->toBeTrue();

    $testCase = test();
    expect(EnumMapper::isValidMapper(ConstantMapperClassEnum::class, $testCase))->toBeFalse();
    expect(EnumMapper::isValidMapper(ConstantMapperClassEnum::class, $testCase::class))->toBeFalse();

    $mapper = new class extends Mapper {
        protected function mappable(): array
        {
            return [];
        }
    };

    expect(EnumMapper::isValidMapper(ConstantMapperClassEnum::class, $mapper))->toBeTrue();

    expect(EnumMapper::isValidMapper(ConstantMapperClassEnum::class, $mapper::class))->toBeTrue();
});