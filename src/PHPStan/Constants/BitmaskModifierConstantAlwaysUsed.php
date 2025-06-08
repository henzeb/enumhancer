<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;
use function strtolower;

class BitmaskModifierConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{

    public function isAlwaysUsed(ConstantReflection $constant): bool
    {
        if (strtolower($constant->getName()) !== 'bit_modifier') {
            return false;
        }

        $class = $constant->getDeclaringClass();

        if (!$class->hasConstant('BIT_VALUES')) {
            return false;
        }

        if (!$class->getBackedEnumType()?->isInteger()) {
            return false;
        }


        return EnumImplements::bitmasks($class->getName());
    }
}
