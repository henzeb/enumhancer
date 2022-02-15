<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\Labels;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedEnum;
use Mockery;
use PHPUnit\Framework\TestCase;


class LabelsTest extends TestCase
{
    public function testShouldGetNameWhenNoLabelsSpecifiedAtAll()
    {
        $enum = Mockery::mock(Labels::class);
        $enum->name = 'MY_ENUM';

        $this->assertEquals('MY_ENUM', $enum->label());
    }

    public function testShouldGetLabelByName()
    {
        $this->assertEquals('My label', EnhancedEnum::ENUM->label());
    }

    public function testShouldGetNameWhenLabelDoesNotExist()
    {
        $this->assertEquals('ANOTHER_ENUM', EnhancedEnum::ANOTHER_ENUM->label());
    }
}
