<?php

namespace Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\ExtractBackedEnum;

class ExtractorTest extends TestCase
{
    public function testShouldFindEnumInText()
    {
        $this->assertEquals(
            [
                ExtractBackedEnum::AN_ENUM
            ],
            ExtractBackedEnum::extract('this is an enum test text')
        );
    }

    public function testShouldFindEnumInTextCaseInsensitive()
    {
        $this->assertEquals(
            [
                ExtractBackedEnum::AN_ENUM
            ],
            ExtractBackedEnum::extract('this is an ENUM test text')
        );
    }

    public function testShouldNotFindEnumInText()
    {
        $this->assertEquals(
            [],
            ExtractBackedEnum::extract('This text contains nothing')
        );
    }

    public function testShouldFindMultipleEnumInText()
    {
        $this->assertEquals(
            [
                ExtractBackedEnum::AN_ENUM,
                ExtractBackedEnum::ANOTHER_ENUM
            ],
            ExtractBackedEnum::extract('this is an enum test and this is another enum text')
        );
    }

    public function testUniqueMultipleEnumsInText()
    {
        $this->assertEquals(
            [
                ExtractBackedEnum::AN_ENUM,
                ExtractBackedEnum::AN_ENUM,
                ExtractBackedEnum::AN_ENUM
            ],
            ExtractBackedEnum::extract('an enum An ENUM an enum')
        );
    }

    public function testShouldNotMatchPartsOfWords()
    {
        $this->assertEquals(
            [
                ExtractBackedEnum::AN_ENUM,
                ExtractBackedEnum::AN_ENUM
            ],
            ExtractBackedEnum::extract('an enums An ENUM an enum')
        );
    }

    public function testExtractionWithUnitEnum()
    {
        $this->assertEquals(
            [
                SubsetUnitEnum::ENUM,
                SubsetUnitEnum::ENUM,
                SubsetUnitEnum::ENUM,
            ],
            SubsetUnitEnum::extract('an enum An ENUM an EnUm')
        );
    }
}
