<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Helpers\Subset\EnumSubsetMethods;
use UnitEnum;

/**
 * @internal
 */
final class EnumCompare
{
    public static function equals(UnitEnum $compare, UnitEnum|int|string|null ...$with): bool
    {
        return (new EnumSubsetMethods($compare::class, $compare))
            ->equals(...$with);
    }

    public static function isValidCall(string $class, string $name, array $arguments): bool
    {
        EnumCheck::check($class);

        return EnumImplements::comparison($class)
            && !count($arguments) && str_starts_with(strtolower($name), 'is')
            && self::getValueFromString($class, $name);
    }

    private static function getValueFromString(string $class, string $name): ?UnitEnum
    {
        return EnumGetters::tryGet(
            $class,
            substr($name, str_starts_with(strtolower($name), 'isnot') ? 5 : 2),
            true,
            false
        );
    }

    public static function call(UnitEnum $enum, string $name): bool
    {
        $value = self::getValueFromString($enum::class, $name);

        return str_starts_with($name, 'isNot') !== self::equals($enum, $value);
    }
}
