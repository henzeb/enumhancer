<?php

namespace Henzeb\Enumhancer\Helpers;

use RuntimeException;

class EnumCheck
{
    public static function check(string $enum): void
    {
        if(!enum_exists($enum, true)) {
            self::throwError($enum);
        }
    }

    private static function throwError(string $enum): never
    {
        throw new RuntimeException('The trait is not being used in an enum');
    }
}
