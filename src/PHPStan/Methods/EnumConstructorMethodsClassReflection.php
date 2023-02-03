<?php

namespace Henzeb\Enumhancer\PHPStan\Methods;

use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\PHPStan\Reflections\ClosureMethodReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ClosureType;
use PHPStan\Type\ObjectType;

class EnumConstructorMethodsClassReflection implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (!$classReflection->isEnum()) {
            return false;
        }

        $className = $classReflection->getName();

        if (!EnumImplements::constructor($className)) {
            return false;
        }

        return EnumGetters::tryGet($className, $methodName, useDefault: false) !== null;
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
            ),
            true
        );
    }
}
