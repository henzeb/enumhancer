<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumGetters;
use UnitEnum;

trait From
{
    public static function from(UnitEnum|string $key): self
    {
        return EnumGetters::get(static::class, $key, $key instanceof UnitEnum, true);
    }

    public static function tryFrom(UnitEnum|string $key): ?self
    {
        return EnumGetters::tryGet(static::class, $key, $key instanceof UnitEnum);
    }
}
