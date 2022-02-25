<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMakers;

trait From
{
    public static function from(string $key): static
    {
        return EnumMakers::make(static::class, $key);
    }

    public static function tryFrom(string $key): ?static
    {
        return EnumMakers::tryMake(static::class, $key);
    }
}
