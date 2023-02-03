<?php

namespace Henzeb\Enumhancer\Helpers;

use BadMethodCallException;
use UnitEnum;

/**
 * @internal
 */
final class EnumMagicCalls
{
    public static function call(UnitEnum $enum, string $name, array $arguments): mixed
    {
        EnumCheck::check($enum::class);

        if (EnumMacros::hasMacro($enum::class, $name)) {
            return EnumMacros::call($enum, $name, $arguments);
        }

        if (EnumCompare::isValidCall($enum::class, $name, $arguments)) {
            return EnumCompare::call($enum, $name);
        }

        if (EnumState::isValidCall($enum::class, $name)) {
            return EnumState::call($enum, $name, $arguments);
        }

        return self::static($enum::class, $name, $arguments);
    }

    public static function static(string $enum, string $name, array $arguments): mixed
    {
        EnumCheck::check($enum);

        if (EnumMacros::hasMacro($enum, $name)) {
            return EnumMacros::callStatic($enum, $name, $arguments);
        }

        if (EnumImplements::constructor($enum)) {
            return EnumGetters::tryGet($enum, $name, true, false) ?? self::throwException($enum, $name);
        }

        self::throwException($enum, $name);
    }

    public static function throwException(string $class, string $name): never
    {
        throw new BadMethodCallException(sprintf('Call to undefined method %s::%s(...)', $class, $name));
    }
}
