<?php

namespace Henzeb\Enumhancer\PHPStan\Constants;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PHPStan\Reflection\ConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;
use function str_starts_with;
use function strtolower;

class MapperConstantAlwaysUsed implements AlwaysUsedClassConstantsExtension
{
    public function isAlwaysUsed(ConstantReflection $constant): bool
    {
        $class = $constant->getDeclaringClass();

        if (!$class->isEnum()) {
            return false;
        }

        $className = $class->getName();
        $constantName = $constant->getName();

        if (str_starts_with(strtolower($constantName), 'map')) {
            return EnumImplements::mappers($className);
        }

        return false;
    }
}
