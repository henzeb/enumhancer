<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\Subset\EnumSubsetMethods;
use UnitEnum;

trait Subset
{
    /**
     * @param static[] $enums
     * @return EnumSubsetMethods<self>
     */
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

    /**
     * @param static[] $enums
     * @return EnumSubsetMethods<static>
     */
    public static function of(self ...$enums): EnumSubsetMethods
    {
        return new EnumSubsetMethods(
            self::class,
            ...($enums ?: self::cases())
        );
    }
}
