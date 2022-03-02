<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\EnumSubset;
use Henzeb\Enumhancer\Helpers\EnumSubsetMethods;

trait Subset
{
    final public static function of(self ...$enums): EnumSubset
    {
        return new EnumSubsetMethods(self::class, ...($enums ?:self::cases()));
    }
}
