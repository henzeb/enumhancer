<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Methods;

use Henzeb\Enumhancer\PHPStan\Methods\EnumStateMethodsClassReflection;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\ObjectType;
use stdClass;

class EnumStateMethodsClassReflectionTest extends PHPStanTestCase
{
    public function testReturnsFalseIfNotEnum(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(stdClass::class);

        $this->assertFalse(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseIfNotImplementingState(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(SimpleEnum::class);

        $this->assertFalse(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseWithIncorrectMethod(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(StateElevatorEnum::class);

        $this->assertFalse(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'toHearts'
            )
        );

        $this->assertFalse(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'tryToHearts'
            )
        );
    }

    public function testReturnsTrueWithCorrectMethod(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(StateElevatorEnum::class);

        $this->assertTrue(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'toOpen'
            )
        );

        $this->assertTrue(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'tryToOpen'
            )
        );
    }

    public function testGetMethod(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(StateElevatorEnum::class);

        $methodReflection = (new EnumStateMethodsClassReflection())->getMethod(
            $reflection,
            'toOpen'
        );

        $this->assertTrue($reflection === $methodReflection->getDeclaringClass());

        $this->assertFalse($methodReflection->isStatic());

        $this->assertEquals([], $methodReflection->getVariants()[0]->getParameters());
        $this->assertEquals(
            new ObjectType(StateElevatorEnum::class),
            $methodReflection->getVariants()[0]->getReturnType()
        );

    }
}
