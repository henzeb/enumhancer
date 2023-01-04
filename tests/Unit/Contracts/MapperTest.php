<?php

namespace Henzeb\Enumhancer\Tests\Unit\Contracts;

use Closure;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use PHPUnit\Framework\TestCase;


class MapperTest extends TestCase
{
    public function getMapper(array $data = [])
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

    public function testReturnsNull()
    {
        $this->assertNull($this->getMapper()->map('map'));
    }

    public function testReturnsNullExistingKeyWithNull()
    {
        $this->assertNull($this->getMapper(['map' => null])->map('map'));
    }

    public function testReturnsNullExistingKeyWithString()
    {
        $this->assertNull($this->getMapper(['map' => ''])->map('map'));
    }

    public function testReturnsMappedString()
    {
        $this->assertEquals(
            'thisIsmapped',
            $this->getMapper(['map' => 'thisIsmapped'])->map('map')
        );
    }

    public function testReturnsMappedInteger()
    {
        $this->assertEquals(
            0,
            $this->getMapper(['map' => 0])->map('map')
        );

        $this->assertEquals(
            5,
            $this->getMapper(['map' => 5])->map('map')
        );
    }

    public function testReturnsMappedEnum()
    {
        $this->assertEquals(
            'ENUM',
            $this->getMapper(['map' => EnhancedBackedEnum::ENUM])->map('map')
        );
    }

    public function testReturnsUnprefixedStringWithPrefix()
    {
        $this->assertEquals(
            'thisIsmapped',
            $this->getMapper(['map' => 'thisIsmapped'])->map('map', 'prefix')
        );
    }

    public function testReturnsMappedNullWithPrefix()
    {
        $this->assertEquals(
            null,
            $this->getMapper(['map' => ['map' => 'ENUM']])->map('map')
        );
    }

    public function testReturnsMappedStringWithPrefix()
    {
        $this->assertEquals(
            'ENUM',
            $this->getMapper(['Map' => ['map' => 'ENUM']])->map('map', 'Map')
        );
    }

    public function testReturnsMappedStringWithDifferentPrefix()
    {
        $this->assertEquals(
            'ENUM',
            $this->getMapper(['prefix' => ['map' => 'ENUM']])->map('map', 'prefix')
        );
    }

    public function testReturnsMappedEnumWithPrefix()
    {
        $this->assertEquals(
            'ENUM',
            $this->getMapper(['prefix' => ['map' => EnhancedBackedEnum::ENUM]])->map('map', 'prefix')
        );
    }

    public function testReturnsNullExsistingKeyNullWithPrefix()
    {
        $this->assertNull(
            $this->getMapper(['prefix' => ['map' => null]])->map('map', 'prefix')
        );
    }

    public function testReturnsNullWithExistingKeyStringPrefix()
    {
        $this->assertNull(
            $this->getMapper(['prefix' => ['map' => '']])->map('map', 'prefix')
        );
    }

    public function testIsDefined()
    {
        $this->assertTrue(
            $this->getMapper(['defined' => 'this is defined'])->defined('defined')
        );
    }

    public function testIsNotDefined()
    {
        $this->assertFalse(
            $this->getMapper(['defined' => 'this is defined'])->defined('notDefined')
        );
    }

    public function testIsNotDefinedExistingKey()
    {
        $this->assertFalse(
            $this->getMapper(['defined' => null])->defined('defined')
        );
    }

    public function testIsNotDefinedExistingKeyString()
    {
        $this->assertFalse(
            $this->getMapper(['defined' => ''])->defined('defined')
        );
    }

    public function testIsDefinedWithPrefix()
    {
        $this->assertTrue(
            $this->getMapper(['prefix' => ['defined' => 'this is defined']])->defined('defined', 'prefix')
        );
    }

    public function testIsDefinedWithoutPrefixWhileGiven()
    {
        $this->assertTrue(
            $this->getMapper(['defined' => 'this is defined'])->defined('defined', 'prefix')
        );
    }

    public function testReturnsKeys()
    {
        $this->assertEquals(
            $this->getMapper(['defined' => 'this is defined'])->keys(),
            ['defined']
        );
    }

    public function testReturnsKeysWithPrefix()
    {
        $this->assertEquals(
            ['defined', 'prefixed_key'],
            $this->getMapper(
                [
                    'defined' => 'this is defined',
                    'A_prefix' => ['prefixed_key' => 'a_value']
                ]
            )->keys('A_prefix')
        );
    }

    public function testShouldBeCaseAgnosticWithPrefix()
    {
        $this->assertEquals(
            $this->getMapper([
                'defined' => 'this is defined',
                'a_prefix' => ['prefixed_key' => 'value']
            ])->map('PREFIXED_KEY', 'a_prefix'),
            'value'
        );
    }

    public function testShouldBeCaseAgnostic()
    {
        $this->assertEquals(
            'this is defined',
            $this->getMapper([
                'defined' => 'this is defined',
                'a_prefix' => ['prefixed_key' => 'value']
            ])->map('DEfined'),

        );
    }

    public function testShouldAcceptEnums()
    {
        $this->assertEquals(
            'this is defined',
            $this->getMapper([
                'enum' => 'this is defined',
            ])->map(EnhancedBackedEnum::ENUM),

        );
    }

    public function testShouldAcceptEnumsWithPrefix()
    {
        $this->assertEquals(
            'this is defined',
            $this->getMapper([
                'prefixed' => ['enum' => 'this is defined'],
            ])->map(EnhancedBackedEnum::ENUM, 'prefixed'),

        );
    }

    public function testShouldFlip()
    {
        $flipped = $this->getMapper(['defined' => 'undefined', 'prefixed' => ['Dog' => 'Canine']])->makeFlipped();

        $this->assertTrue(
            Closure::bind(function () {
                return $this->flip && $this->flipPrefix === null;
            }, $flipped, Mapper::class)()
        );

        $this->assertEquals(
            'defined',
            $flipped
                ->map('undefined')
        );
    }

    public function testFlippedResultShouldBeCached(): void
    {
        $flipped = $this->getMapper(['defined' => 'undefined', 'prefixed' => ['Dog' => 'Canine']])->makeFlipped();

        $this->assertNull(
            Closure::bind(function () {
                return $this->flipped;
            }, $flipped, Mapper::class)()
        );

        $flipped->map('undefined');

        $this->assertEquals(['undefined' => 'defined'],
            Closure::bind(function () {
                $return = $this->flipped;
                $this->flipped = ['undefined' => 'cached'];
                return $return;
            }, $flipped, Mapper::class)());

        $this->assertEquals('cached', $flipped->map('undefined'));
    }

    public function testShouldFlipWithPrefix()
    {
        $flipped = $this->getMapper(['prefixed' => ['defined' => 'undefined']])->makeFlipped('prefixed');

        $this->assertTrue(
            Closure::bind(function () {
                return $this->flip && $this->flipPrefix === 'prefixed';
            }, $flipped, Mapper::class)()
        );

        $this->assertEquals(
            'defined',
            $flipped
                ->map('undefined')
        );
    }

    public function testShouldFlipStatic()
    {
        $flipped = $this->getMapper(['defined' => 'undefined'])::flip();

        $this->assertTrue(
            Closure::bind(function () {
                return $this->flip && $this->flipPrefix === null;
            }, $flipped, Mapper::class)()
        );

        $this->assertEquals(
            'defined',
            $flipped
                ->map('undefined')
        );
    }

    public function testShouldFlipStaticWithPrefix()
    {
        $flipped = $this->getMapper(['prefixed' => ['defined' => 'undefined']])::flip('prefixed');

        $this->assertTrue(
            Closure::bind(function () {
                return $this->flip && $this->flipPrefix === 'prefixed';
            }, $flipped, Mapper::class)()
        );

        $this->assertEquals(
            'defined',
            $flipped
                ->map('undefined')
        );
    }

    public function testShouldFlipDefined()
    {
        $this->assertEquals(
            true,
            $this->getMapper(['defined' => 'undefined', 'prefixed' => ['Canine' => 'Dog']])->makeFlipped()
                ->defined('undefined')
        );
    }

    public function testShouldFlipDefinedWithPrefix()
    {
        $this->assertEquals(
            true,
            $this->getMapper(['defined' => 'undefined', 'prefixed' => ['Canine' => 'Dog']])
                ->makeFlipped('prefixed')
                ->defined('dog')
        );
    }

    public function testShouldFlipKeys()
    {
        $this->assertEquals(
            ['undefined'],
            $this->getMapper(['defined' => 'undefined'])->makeFlipped()
                ->keys()
        );
    }

    public function testShouldFlipKeysWithPrefix()
    {
        $this->assertEquals(
            ['canine'],
            $this->getMapper(
                [
                    'defined' => 'undefined',
                    'prefixed' => ['Dog' => 'Canine']
                ])->makeFlipped()
                ->keys('prefixed')
        );

        $this->assertEquals(
            ['dog'],
            $this->getMapper(['defined' => 'undefined', 'prefixed' => ['Canine' => 'Dog']])
                ->makeFlipped('prefixed')
                ->keys()
        );
    }

    public function testShouldflipWithEnums(): void
    {
        $mapper = $this->getMapper(
            [
                ConstructableUnitEnum::CALLABLE->name => EnhancedBackedEnum::ENUM
            ]
        )->makeFlipped();

        $this->assertEquals('CALLABLE', $mapper->map(EnhancedBackedEnum::ENUM));
    }

    public function testShouldFlipWithSameValuesShouldUseLastValue(): void
    {
        $mapper = $this->getMapper(
            [
                'Dog' => 'Canine',
                'bulldog' => 'Canine'
            ]
        )->makeFlipped();

        $this->assertEquals('bulldog', $mapper->map('Canine'));
    }

    public function testShouldFailWhenMethodNotexists()
    {
        $this->expectError();
        $this->getMapper([])->DoesNotExist();
    }

    public function testShouldFailWhenStaticMethodNotexists()
    {
        $this->expectError();
        $this->getMapper([])::DoesAlsoNotExist();
    }

    public function testShouldMapStatically(): void
    {
        $map = $this->getMapper(['map' => 'mapped', 'prefixed' => ['maps' => 'mapping']]);
        $this->assertEquals('mapped', $map::map('map'));
        $this->assertEquals('mapping', $map::map('maps', 'prefixed'));
        $this->assertEquals('map', $map::flip()->map('mapped'));

        $this->assertEquals(true, $map::defined('map'));
        $this->assertEquals(true, $map::defined('maps', 'prefixed'));

        $this->assertEquals(['map'], $map::keys());
        $this->assertEquals(['map', 'maps'], $map::keys('prefixed'));
    }
}
