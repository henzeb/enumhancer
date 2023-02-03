<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\BitmaskConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Reflection\ClassReflection;

class BitmaskConstantAlwaysUsedTest extends MockeryTestCase
{
    public function testShouldIgnoreIfNotBitmaskConstant(): void
    {

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getName')->andReturn('not_bit_values');

        $constant = new BitmaskConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testShouldOnlyWorkWithEnums(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnFalse();

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('bit_values');

        $constant = new BitmaskConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testShouldOnlyWorkWithEnumsImplementingBitmask(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturn(SimpleEnum::class);

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('bit_values');

        $constant = new BitmaskConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testShouldReturnTrueWhenImplementingBitmaskAndHasValue(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturn(BitmasksIntEnum::class);

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('bit_values');

        $constant = new BitmaskConstantAlwaysUsed();

        $this->assertTrue($constant->isAlwaysUsed($constantReflection));
    }
}
