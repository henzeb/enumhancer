<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;

class EnumCompare
{

    public static function equals(UnitEnum $compare, UnitEnum|int|string|null ...$with): bool
    {
        return (new EnumSubsetMethods($compare::class, $compare))
            ->equals(...$with);
    }
}
