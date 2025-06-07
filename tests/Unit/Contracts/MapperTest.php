<?php

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;

beforeEach(function () {
    set_error_handler(
        static function (int $errno, string $errstr): void {
            throw new \Exception($errstr, $errno);
        },
        E_USER_ERROR
    );
});

afterEach(function () {
    restore_error_handler();
});

function getTestMapper(array $data = [])
{
    return new class($data) extends Mapper {
        static array $keepData = [];

        public function __construct(private array $data = [])
        {
            if (!empty($this->data)) {
                self::$keepData = $this->data;
            }
            $this->data = self::$keepData;
        }

        public function mappable(): array
        {
            return $this->data;
        }
    };
}

test('returns null', function () {
    expect(getTestMapper()->map('map'))->toBeNull();
});

test('returns null existing key with null', function () {
    expect(getTestMapper(['map' => null])->map('map'))->toBeNull();
});

test('returns null existing key with string', function () {
    expect(getTestMapper(['map' => ''])->map('map'))->toBeNull();
});

test('returns mapped string', function () {
    expect(getTestMapper(['map' => 'thisIsmapped'])->map('map'))->toBe('thisIsmapped');
});

test('returns mapped integer', function () {
    expect(getTestMapper(['map' => 0])->map('map'))->toBeNull();

    expect(getTestMapper(['map' => 5])->map('map'))->toBe(5);
});

test('returns mapped enum', function () {
    expect(getTestMapper(['map' => EnhancedBackedEnum::ENUM])->map('map'))->toBe('ENUM');
});

test('returns unprefixed string with prefix', function () {
    expect(getTestMapper(['map' => 'thisIsmapped'])->map('map', 'prefix'))->toBe('thisIsmapped');
});

test('returns mapped null with prefix', function () {
    expect(getTestMapper(['map' => ['map' => 'ENUM']])->map('map'))->toBeNull();
});

test('returns mapped string with prefix', function () {
    expect(getTestMapper(['Map' => ['map' => 'ENUM']])->map('map', 'Map'))->toBe('ENUM');
});

test('returns mapped string with different prefix', function () {
    expect(getTestMapper(['prefix' => ['map' => 'ENUM']])->map('map', 'prefix'))->toBe('ENUM');
});

test('returns mapped enum with prefix', function () {
    expect(getTestMapper(['prefix' => ['map' => EnhancedBackedEnum::ENUM]])->map('map', 'prefix'))->toBe('ENUM');
});

test('returns null exsisting key null with prefix', function () {
    expect(getTestMapper(['prefix' => ['map' => null]])->map('map', 'prefix'))->toBeNull();
});

test('returns null with existing key string prefix', function () {
    expect(getTestMapper(['prefix' => ['map' => '']])->map('map', 'prefix'))->toBeNull();
});

test('is defined', function () {
    expect(getTestMapper(['defined' => 'this is defined'])->defined('defined'))->toBeTrue();
});

test('is not defined', function () {
    expect(getTestMapper(['defined' => 'this is defined'])->defined('notDefined'))->toBeFalse();
});

test('is not defined existing key', function () {
    expect(getTestMapper(['defined' => null])->defined('defined'))->toBeFalse();
});

test('is not defined existing key string', function () {
    expect(getTestMapper(['defined' => ''])->defined('defined'))->toBeFalse();
});

test('is defined with prefix', function () {
    expect(getTestMapper(['prefix' => ['defined' => 'this is defined']])->defined('defined', 'prefix'))->toBeTrue();
});

test('is defined without prefix while given', function () {
    expect(getTestMapper(['defined' => 'this is defined'])->defined('defined', 'prefix'))->toBeTrue();
});

test('returns keys', function () {
    expect(getTestMapper(['defined' => 'this is defined'])->keys())->toBe(['defined']);
});

test('returns keys with prefix', function () {
    expect(getTestMapper([
        'defined' => 'this is defined',
        'A_prefix' => ['prefixed_key' => 'a_value']
    ])->keys('A_prefix'))->toBe(['defined', 'prefixed_key']);
});

test('should be case agnostic with prefix', function () {
    expect(getTestMapper([
        'defined' => 'this is defined',
        'a_prefix' => ['prefixed_key' => 'value']
    ])->map('PREFIXED_KEY', 'a_prefix'))->toBe('value');
});

test('should be case agnostic', function () {
    expect(getTestMapper([
        'defined' => 'this is defined',
        'a_prefix' => ['prefixed_key' => 'value']
    ])->map('DEfined'))->toBe('this is defined');
});

test('should accept enums', function () {
    expect(getTestMapper([
        'enum' => 'this is defined',
    ])->map(EnhancedBackedEnum::ENUM))->toBe('this is defined');
});

test('should accept enums with prefix', function () {
    expect(getTestMapper([
        'prefixed' => ['enum' => 'this is defined'],
    ])->map(EnhancedBackedEnum::ENUM, 'prefixed'))->toBe('this is defined');
});

test('should flip', function () {
    $flipped = getTestMapper(['defined' => 'undefined', 'prefixed' => ['Dog' => 'Canine']])->makeFlipped();

    $result = \Closure::bind(function () {
        return $this->flip && $this->flipPrefix === null;
    }, $flipped, Mapper::class)();

    expect($result)->toBeTrue();

    expect($flipped->map('undefined'))->toBe('defined');
});

test('flipped result should be cached', function () {
    $flipped = getTestMapper(['defined' => 'undefined', 'prefixed' => ['Dog' => 'Canine']])->makeFlipped();

    $result = \Closure::bind(function () {
        return $this->flipped;
    }, $flipped, Mapper::class)();

    expect($result)->toBeNull();

    $flipped->map('undefined');

    $cachedResult = \Closure::bind(function () {
        $return = $this->flipped;
        $this->flipped = ['undefined' => 'cached'];
        return $return;
    }, $flipped, Mapper::class)();

    expect($cachedResult)->toBe(['undefined' => 'defined']);

    expect($flipped->map('undefined'))->toBe('cached');
});

test('should flip with prefix', function () {
    $flipped = getTestMapper(['prefixed' => ['defined' => 'undefined']])->makeFlipped('prefixed');

    $result = \Closure::bind(function () {
        return $this->flip && $this->flipPrefix === 'prefixed';
    }, $flipped, Mapper::class)();

    expect($result)->toBeTrue();

    expect($flipped->map('undefined'))->toBe('defined');
});

test('should flip static', function () {
    $flipped = getTestMapper(['defined' => 'undefined'])::flip();

    $result = \Closure::bind(function () {
        return $this->flip && $this->flipPrefix === null;
    }, $flipped, Mapper::class)();

    expect($result)->toBeTrue();

    expect($flipped->map('undefined'))->toBe('defined');
});

test('should flip static with prefix', function () {
    $flipped = getTestMapper(['prefixed' => ['defined' => 'undefined']])::flip('prefixed');

    $result = \Closure::bind(function () {
        return $this->flip && $this->flipPrefix === 'prefixed';
    }, $flipped, Mapper::class)();

    expect($result)->toBeTrue();

    expect($flipped->map('undefined'))->toBe('defined');
});

test('should flip defined', function () {
    expect(getTestMapper(['defined' => 'undefined', 'prefixed' => ['Canine' => 'Dog']])->makeFlipped()->defined('undefined'))->toBeTrue();
});

test('should flip defined with prefix', function () {
    expect(getTestMapper(['defined' => 'undefined', 'prefixed' => ['Canine' => 'Dog']])
        ->makeFlipped('prefixed')
        ->defined('dog'))->toBeTrue();
});

test('should flip keys', function () {
    expect(getTestMapper(['defined' => 'undefined'])->makeFlipped()->keys())->toBe(['undefined']);
});

test('should flip keys with prefix', function () {
    expect(getTestMapper([
        'defined' => 'undefined',
        'prefixed' => ['Dog' => 'Canine']
    ])->makeFlipped()->keys('prefixed'))->toBe(['canine']);

    expect(getTestMapper(['defined' => 'undefined', 'prefixed' => ['Canine' => 'Dog']])
        ->makeFlipped('prefixed')
        ->keys())->toBe(['dog']);
});

test('should flip with enums', function () {
    $mapper = getTestMapper([
        ConstructableUnitEnum::CALLABLE->name => EnhancedBackedEnum::ENUM
    ])->makeFlipped();

    expect($mapper->map(EnhancedBackedEnum::ENUM))->toBe('CALLABLE');
});

test('should flip with same values should use last value', function () {
    $mapper = getTestMapper([
        'Dog' => 'Canine',
        'bulldog' => 'Canine'
    ])->makeFlipped();

    expect($mapper->map('Canine'))->toBe('bulldog');
});

test('should fail when method not exists', function () {
    getTestMapper([])->DoesNotExist();
})->throws(\Exception::class);

test('should fail when static method not exists', function () {
    getTestMapper([])::DoesAlsoNotExist();
})->throws(\Exception::class);

test('should map statically', function () {
    $map = getTestMapper(['map' => 'mapped', 'prefixed' => ['maps' => 'mapping']]);
    
    expect($map::map('map'))->toBe('mapped');
    expect($map::map('maps', 'prefixed'))->toBe('mapping');
    expect($map::flip()->map('mapped'))->toBe('map');

    expect($map::defined('map'))->toBeTrue();
    expect($map::defined('maps', 'prefixed'))->toBeTrue();

    expect($map::keys())->toBe(['map']);
    expect($map::keys('prefixed'))->toBe(['map', 'maps']);
});