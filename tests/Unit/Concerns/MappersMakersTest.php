<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * @deprecated
 */
class MappersMakersTest extends TestCase
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
        EnhancedBackedEnum::makeArray([null], $this->getMapper());
    }

    public function testGetArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::makeArray(['ENUM'])
        );
    }

    public function testGetArrayShouldThrowErrorWorkWithoutMapper()
    {
        $this->expectError();
        EnhancedBackedEnum::makeArray(['Does Not exist']);
    }

    public function testGetArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray(['mappedEnum'], $this->getMapper())
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
        EnhancedBackedEnum::makeArray(['ENUM', 'doesNotExist'], $this->getMapper());
    }

    public function testTryGetArrayShouldWorkWithoutMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray(['ENUM', 'DoesNotExist'])
        );
    }

    public function testTryGetArrayShouldNotMapWhenNull()
    {
        $this->assertEquals([], EnhancedBackedEnum::tryMakeArray([null], $this->getMapper()));
    }

    public function testTryGetArrayShouldWorkWitMapper()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray(['mappedEnum', 'DoesNotExist'], $this->getMapper())
        );
    }

    public function testtryArrayShouldMapWithoutMapperGiven()
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray(['anotherMappedEnum'])
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
            EnhancedBackedEnum::tryMake(EnhancedBackedEnum::ENUM)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ANOTHER_ENUM,
            EnhancedBackedEnum::tryMake(EnhancedUnitEnum::Mapped)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::tryMake(EnhancedUnitEnum::ENUM)
        );

        $this->assertNull(
            EnhancedBackedEnum::tryMake(EnhancedUnitEnum::Unique)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::make(EnhancedBackedEnum::ENUM)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ANOTHER_ENUM,
            EnhancedBackedEnum::make(EnhancedUnitEnum::Mapped)
        );

        $this->assertEquals(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::make(EnhancedUnitEnum::ENUM)
        );

        $this->expectException(ValueError::class);
        EnhancedBackedEnum::make(EnhancedUnitEnum::Unique);
    }

    public function testShouldAcceptEnumsAsValueArrays(): void
    {
        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray([EnhancedBackedEnum::ENUM])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ANOTHER_ENUM],
            EnhancedBackedEnum::tryMakeArray([EnhancedUnitEnum::Mapped])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray([EnhancedUnitEnum::ENUM])
        );

        $this->assertEquals(
            [],
            EnhancedBackedEnum::tryMakeArray([EnhancedUnitEnum::Unique])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::tryMakeArray([EnhancedBackedEnum::ENUM])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ENUM],
            EnhancedBackedEnum::makeArray([EnhancedUnitEnum::ENUM])
        );

        $this->assertEquals(
            [EnhancedBackedEnum::ANOTHER_ENUM],
            EnhancedBackedEnum::makeArray([EnhancedUnitEnum::Mapped])
        );

        $this->expectException(ValueError::class);
        EnhancedBackedEnum::makeArray([EnhancedUnitEnum::Unique]);
    }
}
