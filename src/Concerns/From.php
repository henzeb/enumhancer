<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMakers;

trait From
{
    final public static function from(string $key): static
    {
        return EnumMakers::make(static::class, $key, useDefault: false);
    }

    final public static function tryFrom(string $key): ?static
    {
        return EnumMakers::tryMake(static::class, $key, useDefault: false);
    }
}
