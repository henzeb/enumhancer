<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\StrictConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Value\ValueStrictEnum;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Reflection\ClassReflection;

class StrictConstantAlwaysUsedTest extends MockeryTestCase
{
    public function testShouldIgnoreIfNotStrictConstant(): void
    {
        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getName')->andReturn('notStrict');

        $constant = new StrictConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testShouldOnlyWorkWithEnums(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->twice()->andReturnFalse();

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->twice()->andReturn($classReflection);
        $constantReflection->expects('getName')->twice()->andReturn('strict', 'STRICT');

        $constant = new StrictConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testShouldReturnTrueWithEnumsImplementingValue(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturn(ValueStrictEnum::class);

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('STRICT');

        $constant = new StrictConstantAlwaysUsed();

        $this->assertTrue($constant->isAlwaysUsed($constantReflection));
    }

    public function testShouldReturnFalseWithEnumsNotImplementingValue(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturn(SimpleEnum::class);

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('STRICT');

        $constant = new StrictConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }
}
