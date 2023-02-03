<?php

namespace Henzeb\Enumhancer\PHPStan\Methods;

use Henzeb\Enumhancer\Helpers\EnumState;
use Henzeb\Enumhancer\PHPStan\Reflections\ClosureMethodReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ClosureType;
use PHPStan\Type\ObjectType;

class EnumStateMethodsClassReflection implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (!$classReflection->isEnum()) {
            return false;
        }

        return EnumState::isValidCall(
            $classReflection->getName(),
            $methodName
        );
    }

    public function getMethod(
        ClassReflection $classReflection,
        string $methodName
    ): MethodReflection {

        return new ClosureMethodReflection(
            $classReflection,
            $methodName,
            new ClosureType(
                [],
                new ObjectType($classReflection->getName()),
                false
            )
        );
    }
}
