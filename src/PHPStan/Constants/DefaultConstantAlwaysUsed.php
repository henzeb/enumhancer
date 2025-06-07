<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;

class DefaultConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{
    public function isAlwaysUsed(ClassConstantReflection $constant): bool
    {
        $constantName = $constant->getName();

        if (\strtolower($constantName) === 'default') {
            $class = $constant->getDeclaringClass();
            return $class->isEnum() && EnumImplements::defaults($class->getName());
        }

        return false;
    }
}
