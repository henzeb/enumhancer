<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMakers;

trait Constructor
{
    final public static function __callStatic(string $name, array $arguments)
    {
        return EnumMakers::make(self::class, $name, true);
    }
}
