<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumGetters;

trait Constructor
{
    public static function __callStatic(string $name, array $arguments)
    {
        return EnumGetters::get(self::class, $name, true);
    }
}
