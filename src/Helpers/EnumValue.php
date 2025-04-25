<?php

namespace Henzeb\Enumhancer\Helpers;

use ReflectionClass;
use UnitEnum;

/**
 * @internal
 */
final class EnumValue
{
    public static function value(UnitEnum $enum, ?bool $keepCase = null): string|int
    {
        if (is_null($keepCase)) {
            $keepCase = self::isStrict($enum);
        }

        return $enum->value ?? ($keepCase ? $enum->name : strtolower($enum->name));
    }

    public static function key(UnitEnum $enum): int
    {
        if (property_exists($enum, 'value') && is_numeric($enum->value)) {
            return (int)$enum->value;
        }

        return (int)array_search($enum, $enum::cases());
    }

    private static function isStrict(UnitEnum $enum): bool
    {
        $constants = (new ReflectionClass($enum))->getConstants();

        foreach ($constants as $name => $constant) {
            if ('strict' === strtolower($name) && is_bool($constant)) {
                return $constant;
            }
        }

        return false;
    }
}
