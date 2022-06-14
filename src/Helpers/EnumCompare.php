<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;

abstract class EnumCompare
{
    public static function equals(UnitEnum $compare, UnitEnum|int|string|null ...$with): bool
    {
        return (new EnumSubsetMethods($compare::class, $compare))
            ->equals(...$with);
    }

    public static function isValidCall(string $class, $name, array $arguments): bool
    {
        $nameIsEnum = !EnumMakers::tryMake($class, $name, true);
        return ((!str_starts_with($name, 'is') && !str_starts_with($name, 'isNot'))
                || count($arguments))
            && $nameIsEnum;
    }
}
