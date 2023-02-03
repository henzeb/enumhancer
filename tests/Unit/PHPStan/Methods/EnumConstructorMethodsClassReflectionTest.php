<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Methods;

use Henzeb\Enumhancer\PHPStan\Methods\EnumConstructorMethodsClassReflection;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;

class EnumConstructorMethodsClassReflectionTest extends MockeryTestCase
{
    public function testReturnsFalseIfNotEnum(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->andReturnFalse();

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseIfNotImplementingConstructor(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->andReturnTrue();
        $reflection->expects('getName')->andReturns(SimpleEnum::class);

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseIfCaseDoesNotExists(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->andReturnTrue();
        $reflection->expects('getName')->andReturns(ConstructableUnitEnum::class);

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsTrueIfCaseDoesExists(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->andReturnTrue();
        $reflection->expects('getName')->andReturns(ConstructableUnitEnum::class);

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'isCallable'
            )
        );
    }

    public function testGetMethod(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);
        $reflection->expects('getName')->andReturns(ConstructableUnitEnum::class);

        $methodReflection = (new EnumConstructorMethodsClassReflection())->getMethod(
            $reflection,
            'isCallable'
        );

        $this->assertTrue($reflection === $methodReflection->getDeclaringClass());

        $this->assertTrue($methodReflection->isStatic());

        $this->assertEquals([], $methodReflection->getVariants()[0]->getParameters());
        $this->assertEquals(
            new ObjectType(ConstructableUnitEnum::class),
            $methodReflection->getVariants()[0]->getReturnType()
        );

    }
}
