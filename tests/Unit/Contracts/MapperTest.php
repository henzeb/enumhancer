<?php

namespace Unit\Contracts;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedEnum;
use PHPUnit\Framework\TestCase;


class MapperTest extends TestCase
{

    public function getMapper(array $data = [])
    {
        return new class($data) extends Mapper {
            public function __construct(private array $data = [])
            {
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

    public function testReturnsMappedString()
    {
        $this->assertEquals(
            'thisIsmapped',
            $this->getMapper(['map' => 'thisIsmapped'])->map('map')
        );
    }

    public function testReturnsMappedEnum()
    {
        $this->assertEquals(
            'ENUM',
            $this->getMapper(['map' => EnhancedEnum::ENUM])->map('map')
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
            $this->getMapper(['map' => ['map' => 'ENUM']])->map('map', 'map')
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
            $this->getMapper(['prefix' => ['map' => EnhancedEnum::ENUM]])->map('map', 'prefix')
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

    public function testIsDefinedWithPrefix()
    {
        $this->assertTrue(
            $this->getMapper(['prefix'=>['defined' => 'this is defined']])->defined('defined', 'prefix')
        );
    }

    public function testIsDefinedWithoutPrefixWhileGiven()
    {
        $this->assertTrue(
            $this->getMapper(['defined' => 'this is defined'])->defined('defined', 'prefix')
        );
    }


}
