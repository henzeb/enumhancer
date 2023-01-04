<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\EnumSubset;
use Henzeb\Enumhancer\Helpers\Subset\EnumSubsetMethods;
use UnitEnum;

trait Subset
{
    public static function without(self ...$enums): EnumSubsetMethods
    {
        return new EnumSubsetMethods(
            self::class,
            ...array_filter(
                self::cases(),
                function (UnitEnum $case) use ($enums) {
                    return !in_array($case, $enums);
                }
            )
        );
    }

    public static function of(self ...$enums): EnumSubset
    {
        return new EnumSubsetMethods(
            self::class,
            ...($enums ?: self::cases())
        );
    }
}
