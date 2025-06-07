<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;

class StrictConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{

    public function isAlwaysUsed(ClassConstantReflection $constant): bool
    {
        if (\strtolower($constant->getName()) !== 'strict') {
            return false;
        }

        $class = $constant->getDeclaringClass();

        if (!$class->isEnum()) {
            return false;
        }

        return EnumImplements::enumhancer($class->getName());
    }
}
