<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMakers;

trait Constructor
{
    public static function __callStatic(string $name, array $arguments)
    {
        if(method_exists(self::class, 'make')) {
            return self::make($name);
        }

        return EnumMakers::make(self::class, $name);
    }
}
