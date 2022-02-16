<?php

namespace Henzeb\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedEnum;
use PHPUnit\Framework\TestCase;


class MappersTest extends TestCase
{
    public function getMapper()
    {
        return new class extends Mapper {


            public function mappable(): array
            {
                return [
                    'mappedEnum' => EnhancedEnum::ENUM
                ];
            }
        };
    }

    public function testMakeShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            EnhancedEnum::ENUM,
            EnhancedEnum::make('ENUM')
        );
    }

    public function testMakeShouldErrorWithoutMapper()
    {
        $this->expectError();
        EnhancedEnum::make('mappedEnum');
    }

    public function testMakeShouldMap()
    {
        $this->assertEquals(
            EnhancedEnum::ENUM,
            EnhancedEnum::make('mappedEnum', $this->getMapper())
        );
    }

    public function testMakeShouldNotMapWhenNull()
    {
        $this->expectError();
        EnhancedEnum::make(null, $this->getMapper());
    }

    public function testMakeShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            EnhancedEnum::ENUM,
            EnhancedEnum::make('anotherMappedEnum')
        );
    }

    public function testMakeShouldErrorWithMap()
    {
        $this->expectError();
        EnhancedEnum::make('not existing', $this->getMapper());
    }

    public function testTryMakeShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            EnhancedEnum::ENUM,
            EnhancedEnum::tryMake('ENUM')
        );
    }

    public function testTryMakeShouldReturnNullWithoutMapper()
    {
        $this->assertNull(EnhancedEnum::tryMake('mappedEnum'));
    }

    public function testTryMakeShouldNotMapWhenNull()
    {

        $this->assertNull(
            EnhancedEnum::tryMake(null, $this->getMapper())
        );
    }

    public function testTryMakeShouldMap()
    {
        $this->assertEquals(
            EnhancedEnum::ENUM,
            EnhancedEnum::tryMake('mappedEnum', $this->getMapper())
        );
    }

    public function testTryMakeShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            EnhancedEnum::ENUM,
            EnhancedEnum::tryMake('anotherMappedEnum')
        );
    }


    public function testTryMakeShouldReturnNullWithMap()
    {
        $this->assertNull(EnhancedEnum::tryMake('not existing', $this->getMapper()));
    }


    public function testMakeArrayShouldNotMapWhenNull()
    {
        $this->expectError();
        EnhancedEnum::makeArray([null], $this->getMapper());
    }

    public function testMakeArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedEnum::ENUM],
            EnhancedEnum::makeArray(['ENUM'])
        );
    }

    public function testMakeArrayShouldThrowErrorWorkWithoutMapper()
    {
        $this->expectError();
        EnhancedEnum::makeArray(['Does Not exist']);
    }

    public function testMakeArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedEnum::ENUM],
            EnhancedEnum::tryMakeArray(['mappedEnum'], $this->getMapper())
        );
    }


    public function testMakeArrayShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            [EnhancedEnum::ENUM],
            EnhancedEnum::MakeArray(['anotherMappedEnum'])
        );
    }

    public function testMakeArrayShouldThrowErrorWitMapper()
    {
        $this->expectError();
        EnhancedEnum::makeArray(['ENUM','doesNotExist'], $this->getMapper());
    }

    public function testTryMakeArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedEnum::ENUM],
            EnhancedEnum::tryMakeArray(['ENUM', 'DoesNotExist'])
        );
    }

    public function testTryMakeArrayShouldNotMapWhenNull()
    {
        $this->assertEquals([], EnhancedEnum::tryMakeArray([null], $this->getMapper()));
    }

    public function testTryMakeArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedEnum::ENUM],
            EnhancedEnum::tryMakeArray(['mappedEnum','DoesNotExist'], $this->getMapper())
        );
    }

    public function testTryMakeArrayShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            [EnhancedEnum::ENUM],
            EnhancedEnum::TryMakeArray(['anotherMappedEnum'])
        );
    }
}
