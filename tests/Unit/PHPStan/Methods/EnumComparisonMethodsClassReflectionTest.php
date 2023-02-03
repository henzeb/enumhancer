<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Methods;

use Henzeb\Enumhancer\PHPStan\Methods\EnumComparisonMethodsClassReflection;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\BooleanType;

class EnumComparisonMethodsClassReflectionTest extends MockeryTestCase
{
    public function testReturnsFalseIfNotEnum(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->andReturnFalse();

        $this->assertFalse(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isHearts'
            )
        );
    }

    public function testReturnsFalseIfNotImplementingComparison(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->twice()->andReturnTrue();
        $reflection->expects('getName')->twice()->andReturns(SimpleEnum::class);

        $this->assertFalse(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isOpen'
            )
        );

        $this->assertFalse(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isNotOpen'
            )
        );
    }

    public function testReturnsFalseWithIncorrectMethod(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->twice()->andReturnTrue();
        $reflection->expects('getName')->twice()->andReturns(EnhancedUnitEnum::class);

        $this->assertFalse(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isHearts'
            )
        );

        $this->assertFalse(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isNotHearts'
            )
        );
    }

    public function testReturnsTrueWithCorrectMethod(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $reflection->expects('isEnum')->twice()->andReturnTrue();
        $reflection->expects('getName')->twice()->andReturns(EnhancedUnitEnum::class);

        $this->assertTrue(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isUnique'
            )
        );

        $this->assertTrue(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isNotUnique'
            )
        );
    }

    public function testGetMethod(): void
    {
        $reflection = Mockery::mock(ClassReflection::class);

        $methodReflection = (new EnumComparisonMethodsClassReflection())->getMethod(
            $reflection,
            'isHearts'
        );

        $this->assertTrue($reflection === $methodReflection->getDeclaringClass());

        $this->assertFalse($methodReflection->isStatic());

        $this->assertEquals([], $methodReflection->getVariants()[0]->getParameters());
        $this->assertEquals(
            new BooleanType(),
            $methodReflection->getVariants()[0]->getReturnType()
        );
    }
}
