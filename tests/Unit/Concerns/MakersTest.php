<?php

namespace Henzeb\Tests\Unit\Concerns;


use Generator;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedMakersEnum;
use PHPUnit\Framework\TestCase;


class MakersTest extends TestCase
{
    public function testExpectValueErrorWhenMakeNull()
    {
        $this->expectError();
        StringBackedMakersEnum::make(null);
    }

    public function testExpectValueErrorWhenMakeUnknownValue()
    {
        $this->expectError();
        StringBackedMakersEnum::make('RANDOM_UNKNOWN_VALUE');
    }

    public function testMake()
    {
        $this->assertEquals(
            StringBackedMakersEnum::TEST,
            StringBackedMakersEnum::make('TEST')
        );
    }

    public function testMakeStrToUpper()
    {
        $this->assertEquals(
            StringBackedMakersEnum::TEST,
            StringBackedMakersEnum::make('test')
        );
    }

    public function testMakeByValue()
    {
        $this->assertEquals(
            StringBackedMakersEnum::TEST1,
            StringBackedMakersEnum::make('Different')
        );
    }

    public function testMakeByValueStrToUpper()
    {
        $this->assertEquals(
            StringBackedMakersEnum::TEST_STRING_TO_UPPER,
            StringBackedMakersEnum::make('stringtoupper')
        );
    }

    public function testMakeByValueOnIntbackedEnum()
    {
        $this->assertEquals(
            IntBackedEnum::TEST,
            IntBackedEnum::make(0)
        );
    }

    public function testTryMakeReturnNullWhenDoesNotExist()
    {
        $this->assertNull(
            StringBackedMakersEnum::tryMake('DOES NOT EXISTS')
        );
    }

    public function testTryMakeByName()
    {
        $this->assertEquals(
            StringBackedMakersEnum::TEST,
            StringBackedMakersEnum::tryMake('TEST')
        );
    }

    public function testTryMakeByValue()
    {
        $this->assertEquals(
            StringBackedMakersEnum::TEST1,
            StringBackedMakersEnum::tryMake('different')
        );
    }

    public function testTryMakeByValueOnIntbackedEnum()
    {
        $this->assertEquals(
            IntBackedEnum::TEST,
            IntBackedEnum::tryMake(0)
        );
    }

    public function testMakeArray()
    {
        $this->assertEquals(
            [
                StringBackedMakersEnum::TEST,
                StringBackedMakersEnum::TEST1,
                StringBackedMakersEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedMakersEnum::makeArray(['TEST', 'different', 'stringtoupper'])
        );
    }

    public function testMakeArrayWithGenerator()
    {
        $this->assertEquals(
            [
                StringBackedMakersEnum::TEST,
                StringBackedMakersEnum::TEST1,
                StringBackedMakersEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedMakersEnum::makeArray(
                (function (): Generator {
                    yield 'TEST';
                    yield 'different';
                    yield 'stringtoupper';
                })()
            )
        );
    }

    public function testMakeArrayFails()
    {
        $this->expectError();

        StringBackedMakersEnum::makeArray(['DOES_NOT_EXIST']);
    }

    public function testTryMakeArray()
    {
        $this->assertEquals(
            [
                StringBackedMakersEnum::TEST,
                StringBackedMakersEnum::TEST1,
                StringBackedMakersEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedMakersEnum::tryMakeArray(['TEST', 'different', 'stringtoupper', 'DOES_NOT_EXIST'])
        );
    }

    public function testTryMakeArrayWithGenerator()
    {
        $this->assertEquals(
            [
                StringBackedMakersEnum::TEST,
                StringBackedMakersEnum::TEST1,
                StringBackedMakersEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedMakersEnum::tryMakeArray(
                (function (): Generator {
                    yield 'TEST';
                    yield 'different';
                    yield 'stringtoupper';
                    yield 'DOES_NOT_EXIST';
                })()
            )
        );
    }
}
