<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\MapperConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers\MappersEnum;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers\NotImplementingMappersEnum;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Reflection\ClassReflection;

class MapperConstantAlwaysUsedTest extends MockeryTestCase
{
    public function testShouldOnlyWorkWithEnums(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnFalse();

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);

        $constant = new MapperConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testShouldReturnFalseWhenNotHavingMapConstant(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturn(MappersEnum::class);

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('NotValid');

        $constant = new MapperConstantAlwaysUsed();

        $this->assertFalse($constant->isAlwaysUsed($constantReflection));
    }

    public function testImplementsMappersMapperConstantWithEnumInstance(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturn(MappersEnum::class);

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('MAP');

        $constant = new MapperConstantAlwaysUsed();

        $this->assertTrue($constant->isAlwaysUsed($constantReflection));
    }

    public function testImplementsMappersMapperConstantWithValidMapper(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturn(MappersEnum::class);

        $constantReflection = Mockery::mock(ClassConstantReflection::class);
        $constantReflection->expects('getDeclaringClass')->andReturn($classReflection);
        $constantReflection->expects('getName')->andReturn('MAP_ARRAY');

        $constant = new MapperConstantAlwaysUsed();

        $this->assertTrue($constant->isAlwaysUsed($constantReflection));
    }
}
