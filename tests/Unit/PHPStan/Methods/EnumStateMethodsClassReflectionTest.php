<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Methods;

use Henzeb\Enumhancer\PHPStan\Methods\EnumStateMethodsClassReflection;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;

class EnumStateMethodsClassReflectionTest extends MockeryTestCase
{
    public function testReturnsFalseIfNotEnum(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->andReturnFalse();

        $this->assertFalse(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseIfNotImplementingState(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->andReturnTrue();
        $reflection->expects('getName')->andReturns(SimpleEnum::class);

        $this->assertFalse(
            (new EnumStateMethodsClassReflection())->hasMethod(
                $reflection,
                'Hearts'
            )
        );
    }

    public function testReturnsFalseWithIncorrectMethod(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->twice()->andReturnTrue();
        $reflection->expects('getName')->twice()->andReturns(StateElevatorEnum::class);

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
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->twice()->andReturnTrue();
        $reflection->expects('getName')->twice()->andReturns(StateElevatorEnum::class);

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
        $reflection = Mockery::mock(ClassReflection::class);
        $reflection->expects('getName')->andReturns(StateElevatorEnum::class);

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
