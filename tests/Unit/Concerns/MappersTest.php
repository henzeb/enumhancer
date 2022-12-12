<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use PHPUnit\Framework\TestCase;
use ValueError;


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
            EnhancedBackedEnum::get('ENUM')
        );
    }

    public function testMakeShouldErrorWithoutMapper()
    {
        $this->expectError();
        EnhancedBackedEnum::get('mappedEnum');
    }

    public function testMakeShouldMap()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::get('mappedEnum', $this->getMapper())
        );
    }

    public function testMakeShouldMapWithStringMap()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::get('mappedEnum', $this->getMapper()::class)
        );
    }

    public function testMakeShouldThrowExceptionForNonMap()
    {
            $this->expectException(\RuntimeException::class);
            EnhancedBackedEnum::get('mappedEnum', self::class);
    }

    public function testMakeShouldNotMapWhenNull()
    {
        $this->expectError();
        EnhancedBackedEnum::get(null, $this->getMapper());
    }

    public function testMakeShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::get('anotherMappedEnum')
        );
    }

    public function testMakeShouldErrorWithMap()
    {
        $this->expectError();
        EnhancedBackedEnum::get('not existing', $this->getMapper());
    }

    public function testTryMakeShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryGet('ENUM')
        );
    }

    public function testTryGetShouldReturnNullWithoutMapper()
    {
        $this->assertNull(EnhancedBackedEnum::tryGet('mappedEnum'));
    }

    public function testTryGetShouldNotMapWhenNull()
    {

        $this->assertNull(
            EnhancedBackedEnum::tryGet(null, $this->getMapper())
        );
    }

    public function testTryGetShouldMap()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryGet('mappedEnum', $this->getMapper())
        );
    }

    public function testTryGetShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryGet('anotherMappedEnum')
        );
    }


    public function testTryGetShouldReturnNullWithMap()
    {
        $this->assertNull(EnhancedBackedEnum::tryGet('not existing', $this->getMapper()));
    }


    public function testGetArrayShouldNotMapWhenNull()
    {
        $this->expectError();
        EnhancedBackedEnum::getArray([null], $this->getMapper());
    }

    public function testGetArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::getArray(['ENUM'])
        );
    }

    public function testGetArrayShouldThrowErrorWorkWithoutMapper()
    {
        $this->expectError();
        EnhancedBackedEnum::getArray(['Does Not exist']);
    }

    public function testGetArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryArray(['mappedEnum'], $this->getMapper())
        );
    }


    public function testGetArrayShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::GetArray(['anotherMappedEnum'])
        );
    }

    public function testGetArrayShouldThrowErrorWitMapper()
    {
        $this->expectError();
        EnhancedBackedEnum::getArray(['ENUM', 'doesNotExist'], $this->getMapper());
    }

    public function testTryGetArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryArray(['ENUM', 'DoesNotExist'])
        );
    }

    public function testTryGetArrayShouldNotMapWhenNull()
    {
        $this->assertEquals([], EnhancedBackedEnum::tryArray([null], $this->getMapper()));
    }

    public function testTryGetArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryArray(['mappedEnum', 'DoesNotExist'], $this->getMapper())
        );
    }

    public function testtryArrayShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryArray(['anotherMappedEnum'])
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

    public function testShouldAcceptEnumsAsValue(): void
    {
        //EnhancedUnitEnum::Mapped->name => EnhancedBackedEnum::ANOTHER_ENUM
        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryGet(EnhancedBackedEnum::ENUM)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ANOTHER_ENUM,
            EnhancedBackedEnum::tryGet(EnhancedUnitEnum::Mapped)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryGet(EnhancedUnitEnum::ENUM)
        );

        $this->assertNull(
            EnhancedBackedEnum::tryGet(EnhancedUnitEnum::Unique)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::get(EnhancedBackedEnum::ENUM)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ANOTHER_ENUM,
            EnhancedBackedEnum::get(EnhancedUnitEnum::Mapped)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::get(EnhancedUnitEnum::ENUM)
        );

        $this->expectException(ValueError::class);
        EnhancedBackedEnum::get(EnhancedUnitEnum::Unique);
    }

    public function testShouldAcceptEnumsAsValueArrays(): void
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryArray([EnhancedBackedEnum::ENUM])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ANOTHER_ENUM],
            EnhancedBackedEnum::tryArray([EnhancedUnitEnum::Mapped])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryArray([EnhancedUnitEnum::ENUM])
        );

        $this->assertEquals(
            [],
            EnhancedBackedEnum::tryArray([EnhancedUnitEnum::Unique])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryArray([EnhancedBackedEnum::ENUM])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::getArray([EnhancedUnitEnum::ENUM])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ANOTHER_ENUM],
            EnhancedBackedEnum::getArray([EnhancedUnitEnum::Mapped])
        );

        $this->expectException(ValueError::class);
        EnhancedBackedEnum::getArray([EnhancedUnitEnum::Unique]);
    }
}
