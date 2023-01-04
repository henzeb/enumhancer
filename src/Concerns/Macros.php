<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMacros;
use stdClass;

trait Macros
{
    use MagicCalls;

    public static function macro(string $name, callable $callable): void
    {
        EnumMacros::macro(self::class, $name, $callable);
    }

    public static function mixin(string|object $mixin): void
    {
        EnumMacros::mixin(self::class, $mixin);
    }

    public static function flushMacros(): void
    {
        EnumMacros::flush(self::class);
    }
}
