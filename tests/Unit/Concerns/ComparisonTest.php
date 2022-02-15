<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\Comparison;
use Mockery;
use PHPUnit\Framework\TestCase;


class ComparisonTest extends TestCase
{
    public function testEnumEquals()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';

        $with = Mockery::mock(Comparison::class);
        $with->name = 'TEST';

        $this->assertTrue(
            $compare->equals($with)
        );
    }

    public function testEnumNotEquals()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';
        $with = clone $compare;
        $compare->name = 'TEST2';
        $this->assertFalse(
            $compare->equals($with)
        );
    }

    /** @noinspection PhpExpressionResultUnusedInspection */
    public function testEqualsDoesNotAcceptDifferentObject()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';

        $class = new class {
            use Comparison;
        };
        $this->expectError();

        $compare->equals($class);
    }

    public function testWhenMultipleValuesAreGivenAndOneIsTrue()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST2';

        $with = [];
        for ($i = 0; $i < 5; $i++) {
            $withObj = Mockery::mock(Comparison::class);
            $withObj->name = 'TEST' . $i;
            $with[] = $withObj;
        }

        $this->assertTrue(
            $compare->equals(...$with)
        );
    }

    public function testWhenMultipleValuesAreGivenAndNoneIsTrue()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';

        $with = [];
        for ($i = 0; $i < 5; $i++) {
            $withObj = Mockery::mock(Comparison::class);
            $withObj->name = 'TEST' . $i;
            $with[] = $withObj;
        }

        $this->assertFalse(
            $compare->equals(...$with)
        );
    }

    public function testWhenStringEqualsName()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';

        $this->assertTrue(
            $compare->equals('TEST')
        );
    }

    public function testWhenStringNotEqualsName()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';

        $this->assertFalse(
            $compare->equals('TEST2')
        );
    }

    public function testWhenStringEqualsValue()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';
        $compare->value = 'test2';

        $this->assertTrue(
            $compare->equals('test2')
        );
    }

    public function testWhenStringNotEqualsValue()
    {
        $compare = Mockery::mock(Comparison::class);
        $compare->name = 'TEST';
        $compare->value = 'test';

        $this->assertFalse(
            $compare->equals('test2')
        );
    }
}
