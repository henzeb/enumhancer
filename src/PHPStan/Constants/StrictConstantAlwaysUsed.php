<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;

class StrictConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{

    public function isAlwaysUsed(ConstantReflection $constant): bool
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
