<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;

class BitmaskConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{

    public function isAlwaysUsed(ConstantReflection $constant): bool
    {
        if ($constant->getName() !== \strtolower('bit_values')) {
            return false;
        }

        $class = $constant->getDeclaringClass();

        if (!$class->isEnum()) {
            return false;
        }

        return EnumImplements::bitmasks($class->getName());
    }
}
