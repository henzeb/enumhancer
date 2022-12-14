<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMagicCalls;

trait MagicCalls
{
    public static function __callStatic(string $name, array $arguments = [])
    {
        return EnumMagicCalls::static(self::class, $name);
    }

    public function __call(string $name, array $arguments = [])
    {
        return EnumMagicCalls::call($this, $name, $arguments);
    }
}
