<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\Defaults;

abstract class EnumImplements
{
    public static function traitOn(string $class, string $implements): bool
    {
        EnumCheck::check($class);

        return in_array($implements, class_uses_recursive($class));
    }

    public static function mappers(string $class): bool
    {
        return self::traitOn($class, Mappers::class);
    }

    public static function defaults(string $class): bool
    {
        return self::traitOn($class, Defaults::class);
    }

    public static function state(string $class): bool
    {
        return self::traitOn($class, State::class);
    }
}
