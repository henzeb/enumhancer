<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\DefaultConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsConstantEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\EnumWithCapitalizedDefault;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\EnumWithDefaultNotImplementing;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ConstantReflection;

class DefaultConstantAlwaysUsedTest extends MockeryTestCase
{
    public function testConstantIsNotDefault()
    {
        $constantReflection = Mockery::mock(ConstantReflection::class);
        $constantReflection->expects('getName')->andReturns('notDefault');

        $constant = new DefaultConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testClassIsNotEnum()
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnFalse();

        $constantReflection = Mockery::mock(ConstantReflection::class);
        $constantReflection->expects('getName')->andReturns('Default');
        $constantReflection->expects('getDeclaringClass')->andReturns($classReflection);

        $constant = new DefaultConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testConstantCorrectDefaultInEnum()
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('getName')->andReturns(DefaultsConstantEnum::class);
        $classReflection->expects('isEnum')->andReturnTrue();

        $constantReflection = Mockery::mock(ConstantReflection::class);
        $constantReflection->expects('getName')->andReturns('Default');
        $constantReflection->expects('getDeclaringClass')->andReturns($classReflection);

        $constant = new DefaultConstantAlwaysUsed();

        $this->assertTrue($constant->isAlwaysUsed($constantReflection));
    }

    public function testConstantCorrectDefaultCapitalizedInEnum()
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('getName')->andReturns(EnumWithCapitalizedDefault::class);
        $classReflection->expects('isEnum')->andReturnTrue();

        $constantReflection = Mockery::mock(ConstantReflection::class);
        $constantReflection->expects('getName')->andReturns('DEFAULT');
        $constantReflection->expects('getDeclaringClass')->andReturns($classReflection);

        $constant = new DefaultConstantAlwaysUsed();

        $this->assertTrue($constant->isAlwaysUsed($constantReflection));
    }

    public function testConstantNamedDefaultNotImplementingDefaults()
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('getName')->andReturns(EnumWithDefaultNotImplementing::class);
        $classReflection->expects('isEnum')->andReturnTrue();

        $constantReflection = Mockery::mock(ConstantReflection::class);
        $constantReflection->expects('getName')->andReturns('Default');
        $constantReflection->expects('getDeclaringClass')->andReturns($classReflection);

        $constant = new DefaultConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }
}
