<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;
use TypeError;
use BackedEnum;

class EnumCheck
{
    public static function check(string $enum): void
    {
        if (!enum_exists($enum, true)) {
            self::throwError();
        }
    }

    public static function matches($class, BackedEnum|UnitEnum|string ...$enums): void
    {
        foreach ($enums as $enum) {
            if (!is_string($enum) && !is_a($enum, $class)) {
                self::throwSameError($class, $enum::class);
            }
        }
    }

    private static function throwError(): never
    {
        throw new TypeError('This method can only be used with an enum');
    }

    private static function throwSameError(string $class, string $enum): never
    {
        throw new TypeError('All enums must be a `' . $class . '`, `' . $enum . '` was given');
    }
}
