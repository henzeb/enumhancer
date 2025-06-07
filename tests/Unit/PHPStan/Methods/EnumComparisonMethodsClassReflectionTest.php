<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Methods;

use Henzeb\Enumhancer\PHPStan\Methods\EnumComparisonMethodsClassReflection;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\BooleanType;
use stdClass;

class EnumComparisonMethodsClassReflectionTest extends PHPStanTestCase
{
    public function testReturnsFalseIfNotEnum(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(stdClass::class);

        $this->assertFalse(
            (new EnumComparisonMethodsClassReflection())->hasMethod(
                $reflection,
                'isHearts'
            )
        );
    }

    public function testReturnsFalseIfNotImplementingComparison(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(SimpleEnum::class);

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
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(EnhancedUnitEnum::class);

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
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(EnhancedUnitEnum::class);

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
        $reflectionProvider = $this->createReflectionProvider();
        $reflection = $reflectionProvider->getClass(EnhancedUnitEnum::class);

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
