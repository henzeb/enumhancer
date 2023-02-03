<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Bitmasks;
use Henzeb\Enumhancer\Concerns\Comparison;
use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\Labels;
use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Concerns\Value;
use Henzeb\Enumhancer\Helpers\Concerns\EnumImplements as EnumImplementsBase;

final class EnumImplements
{
    use EnumImplementsBase;

    public static function mappers(string $class): bool
    {
        return self::implements($class, Mappers::class);
    }

    public static function defaults(string $class): bool
    {
        return self::implements($class, Defaults::class);
    }

    public static function value(string $class): bool
    {
        return self::implements($class, Value::class);
    }

    public static function state(string $class): bool
    {
        return self::implements($class, State::class);
    }

    public static function labels(string $class): bool
    {
        return self::implements($class, Labels::class);
    }

    public static function macros(string $class): bool
    {
        return self::implements($class, Macros::class);
    }

    public static function constructor(string $class): bool
    {
        return self::implements($class, Constructor::class);
    }

    public static function comparison(string $class): bool
    {
        return self::implements($class, Comparison::class);
    }

    public static function bitmasks(string $class): bool
    {
        return self::implements($class, Bitmasks::class);
    }
}
