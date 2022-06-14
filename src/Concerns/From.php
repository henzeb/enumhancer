<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMakers;

trait From
{
    final public static function from(string $key): self
    {
        return EnumMakers::make(static::class, $key, useDefault: true);
    }

    final public static function tryFrom(string $key): ?self
    {
        return EnumMakers::tryMake(static::class, $key);
    }
}
