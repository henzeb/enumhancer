<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use PHPUnit\Framework\TestCase;


class MappersTest extends TestCase
{
    public function getMapper()
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

    public function testMakeShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::make('ENUM')
        );
    }

    public function testMakeShouldErrorWithoutMapper()
    {
        $this->expectError();
        EnhancedBackedEnum::make('mappedEnum');
    }

    public function testMakeShouldMap()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::make('mappedEnum', $this->getMapper())
        );
    }

    public function testMakeShouldMapWithStringMap()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::make('mappedEnum', $this->getMapper()::class)
        );
    }

    public function testMakeShouldThrowExceptionForNonMap()
    {
            $this->expectException(\RuntimeException::class);
            EnhancedBackedEnum::make('mappedEnum', self::class);
    }

    public function testMakeShouldNotMapWhenNull()
    {
        $this->expectError();
        EnhancedBackedEnum::make(null, $this->getMapper());
    }

    public function testMakeShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::make('anotherMappedEnum')
        );
    }

    public function testMakeShouldErrorWithMap()
    {
        $this->expectError();
        EnhancedBackedEnum::make('not existing', $this->getMapper());
    }

    public function testTryMakeShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryMake('ENUM')
        );
    }

    public function testTryMakeShouldReturnNullWithoutMapper()
    {
        $this->assertNull(EnhancedBackedEnum::tryMake('mappedEnum'));
    }

    public function testTryMakeShouldNotMapWhenNull()
    {

        $this->assertNull(
            EnhancedBackedEnum::tryMake(null, $this->getMapper())
        );
    }

    public function testTryMakeShouldMap()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryMake('mappedEnum', $this->getMapper())
        );
    }

    public function testTryMakeShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryMake('anotherMappedEnum')
        );
    }


    public function testTryMakeShouldReturnNullWithMap()
    {
        $this->assertNull(EnhancedBackedEnum::tryMake('not existing', $this->getMapper()));
    }


    public function testMakeArrayShouldNotMapWhenNull()
    {
        $this->expectError();
        EnhancedBackedEnum::makeArray([null], $this->getMapper());
    }

    public function testMakeArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::makeArray(['ENUM'])
        );
    }

    public function testMakeArrayShouldThrowErrorWorkWithoutMapper()
    {
        $this->expectError();
        EnhancedBackedEnum::makeArray(['Does Not exist']);
    }

    public function testMakeArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray(['mappedEnum'], $this->getMapper())
        );
    }


    public function testMakeArrayShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::MakeArray(['anotherMappedEnum'])
        );
    }

    public function testMakeArrayShouldThrowErrorWitMapper()
    {
        $this->expectError();
        EnhancedBackedEnum::makeArray(['ENUM', 'doesNotExist'], $this->getMapper());
    }

    public function testTryMakeArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray(['ENUM', 'DoesNotExist'])
        );
    }

    public function testTryMakeArrayShouldNotMapWhenNull()
    {
        $this->assertEquals([], EnhancedBackedEnum::tryMakeArray([null], $this->getMapper()));
    }

    public function testTryMakeArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray(['mappedEnum', 'DoesNotExist'], $this->getMapper())
        );
    }

    public function testTryMakeArrayShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::TryMakeArray(['anotherMappedEnum'])
        );
    }

    public function testShouldUseMapperWhenConstructorIsUsed()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::anotherMappedEnum()
        );
    }

    public function testShouldExtractWithDefaultMappedKey()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::extract('This text contains anotherMappedEnum for you')
        );
    }

    public function testShouldExtractWithMappedKey()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::extract('This text contains mappedEnum for you', $this->getMapper())
        );
    }

    public function testShouldExtractWithMappedKeyAndDefaultMappedKey()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM, EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::extract(
                'This text contains mappedEnum and anotherMappedEnum for you',
                $this->getMapper()
            )
        );
    }

}
