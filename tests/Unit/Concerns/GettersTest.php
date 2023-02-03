<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;


use Generator;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Getters\GetUnitEnum;
use PHPUnit\Framework\TestCase;


class GettersTest extends TestCase
{
    public function testExpectValueErrorWhenGetNull()
    {
        $this->expectError();
        StringBackedGetEnum::get(null);
    }

    public function testExpectValueErrorWhenGetUnknownValue()
    {
        $this->expectError();
        StringBackedGetEnum::get('RANDOM_UNKNOWN_VALUE');
    }

    public function testGet()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST,
            StringBackedGetEnum::get('TEST')
        );
    }

    public function testGetStrToUpper()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST,
            StringBackedGetEnum::get('test')
        );
    }

    public function testGetByValue()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST1,
            StringBackedGetEnum::get('Different')
        );
    }

    public function testGetByValueStrToUpper()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST_STRING_TO_UPPER,
            StringBackedGetEnum::get('stringtoupper')
        );
    }

    public function testGetByValueOnIntbackedEnum()
    {
        $this->assertEquals(
            IntBackedEnum::TEST,
            IntBackedEnum::get(0)
        );
    }

    public function testTryGetReturnNullWhenDoesNotExist()
    {
        $this->assertNull(
            StringBackedGetEnum::tryGet('DOES NOT EXISTS')
        );
    }

    public function testTryGetByName()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST,
            StringBackedGetEnum::tryGet('TEST')
        );
    }

    public function testTryGetByValue()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST1,
            StringBackedGetEnum::tryGet('different')
        );
    }

    public function testTryGetByValueOnIntbackedEnum()
    {
        $this->assertEquals(
            IntBackedEnum::TEST,
            IntBackedEnum::tryGet(0)
        );
    }

    public function testGetArray()
    {
        $this->assertEquals(
            [
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::getArray(['TEST', 'different', 'stringtoupper'])
        );
    }

    public function testGetArrayWithGenerator()
    {
        $this->assertEquals(
            [
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::getArray(
                (function (): Generator {
                    yield 'TEST';
                    yield 'different';
                    yield 'stringtoupper';
                })()
            )
        );
    }

    public function testGetArrayFails()
    {
        $this->expectError();

        StringBackedGetEnum::getArray(['DOES_NOT_EXIST']);
    }

    public function testTryGetArray()
    {
        $this->assertEquals(
            [
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::tryArray(['TEST', 'different', 'stringtoupper', 'DOES_NOT_EXIST'])
        );
    }

    public function testTryGetArrayWithGenerator()
    {
        $this->assertEquals(
            [
                StringBackedGetEnum::TEST,
                StringBackedGetEnum::TEST1,
                StringBackedGetEnum::TEST_STRING_TO_UPPER
            ],
            StringBackedGetEnum::tryArray(
                (function (): Generator {
                    yield 'TEST';
                    yield 'different';
                    yield 'stringtoupper';
                    yield 'DOES_NOT_EXIST';
                })()
            )
        );
    }

    public function testGetStringBackedEnumWithInteger()
    {
        $this->assertEquals(
            StringBackedGetEnum::TEST1, StringBackedGetEnum::get(1)
        );
    }

    public function testGetUnitEnumWithInteger()
    {
        $this->assertEquals(
            GetUnitEnum::Zero, GetUnitEnum::get(0)
        );
    }

    public function testTryGetShouldReturnDefault(): void
    {
        $this->assertEquals(
            DefaultsEnum::default(),
            DefaultsEnum::tryGet('caseMissing')
        );

        $this->assertEquals(
            [DefaultsEnum::default()],
            DefaultsEnum::tryArray(['caseMissing'])
        );
    }

    public function testTryGetShouldNotReturnDefault(): void
    {
        $this->assertEquals(
            null,
            DefaultsEnum::tryGet('caseMissing', false)
        );
        $this->assertEquals(
            [],
            DefaultsEnum::tryArray(['caseMissing'], false)
        );
    }
}
