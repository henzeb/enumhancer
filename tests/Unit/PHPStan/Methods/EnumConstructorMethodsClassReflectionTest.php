<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Methods;

use Henzeb\Enumhancer\PHPStan\Methods\EnumConstructorMethodsClassReflection;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\ObjectType;
use stdClass;

class EnumConstructorMethodsClassReflectionTest extends PHPStanTestCase
{
    public function testReturnsFalseIfNotEnum(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(stdClass::class);

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseIfNotImplementingConstructor(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(SimpleEnum::class);

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseIfCaseDoesNotExists(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(ConstructableUnitEnum::class);

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsTrueIfCaseDoesExists(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(ConstructableUnitEnum::class);

        $this->assertFalse(
            (new EnumConstructorMethodsClassReflection())->hasMethod(
                $reflection,
                'isCallable'
            )
        );
    }

    public function testGetMethod(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(ConstructableUnitEnum::class);

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
