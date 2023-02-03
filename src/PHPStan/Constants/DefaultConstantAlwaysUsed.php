<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;

class DefaultConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{
    public function isAlwaysUsed(ConstantReflection $constant): bool
    {
        $constantName = $constant->getName();

        if (\strtolower($constantName) === 'default') {
            $class = $constant->getDeclaringClass();
            return $class->isEnum() && EnumImplements::defaults($class->getName());
        }

        return false;
    }
}
