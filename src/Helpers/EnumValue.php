<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;
use BackedEnum;

abstract class EnumValue
{
    public static function value(BackedEnum|UnitEnum $enum, bool $keepCase = false): string|int
    {
        return $enum->value ?? ($keepCase ? $enum->name : strtolower($enum->name));
    }
}
