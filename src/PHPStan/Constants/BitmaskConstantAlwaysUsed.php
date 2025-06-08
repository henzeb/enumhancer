<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;

class BitmaskConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{

    public function isAlwaysUsed(ClassConstantReflection $constant): bool
    {
        if (\strtolower($constant->getName()) !== 'bit_values') {
            return false;
        }

        $class = $constant->getDeclaringClass();

        if (!$class->isEnum()) {
            return false;
        }

        return EnumImplements::bitmasks($class->getName());
    }
}
