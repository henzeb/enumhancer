<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;


use Generator;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Getters\GetUnitEnum;
use PHPUnit\Framework\TestCase;
use ValueError;


class MakersTest extends TestCase
{
    public function testExpectValueErrorWhenMakeNull()
    {
        $this->expectException(ValueError::class);
        StringBackedGetEnum::make(null);
    }

    public function testExpectValueErrorWhenMakeUnknownValue()
    {
        $this->expectException(ValueError::class);
        StringBackedGetEnum::make('RANDOM_UNKNOWN_VALUE');
    }

    public function testMake()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST,
            StringBackedGetEnum::make('TEST')
        );
    }

    public function testMakeStrToUpper()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST,
            StringBackedGetEnum::make('test')
        );
    }

    public function testMakeByValue()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST1,
            StringBackedGetEnum::make('Different')
        );
    }

    public function testMakeByValueStrToUpper()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST_STRING_TO_UPPER,
            StringBackedGetEnum::make('stringtoupper')
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
            StringBackedGetEnum::tryMake('DOES NOT EXISTS')
        );
    }

    public function testTryMakeByName()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST,
            StringBackedGetEnum::tryMake('TEST')
        );
    }

    public function testTryMakeByValue()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST1,
            StringBackedGetEnum::tryMake('different')
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
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::makeArray(['TEST', 'different', 'stringtoupper'])
        );
    }

    public function testMakeArrayWithGenerator()
    {
        $this->assertEquals(
            [
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::makeArray(
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
        $this->expectException(ValueError::class);

        StringBackedGetEnum::makeArray(['DOES_NOT_EXIST']);
    }

    public function testTryMakeArray()
    {
        $this->assertEquals(
            [
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::tryMakeArray(['TEST', 'different', 'stringtoupper', 'DOES_NOT_EXIST'])
        );
    }

    public function testTryMakeArrayWithGenerator()
    {
        $this->assertEquals(
            [
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::tryMakeArray(
                (function (): Generator {
                    yield 'TEST';
                    yield 'different';
                    yield 'stringtoupper';
                    yield 'DOES_NOT_EXIST';
                })()
            )
        );
    }

    public function testMakeStringBackedEnumWithInteger()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST1, StringBackedGetEnum::make(1)
        );
    }

    public function testMakeUnitEnumWithInteger()
    {
        $this->assertEquals(
            GetUnitEnum::Zero, GetUnitEnum::make(0)
        );
    }
}
