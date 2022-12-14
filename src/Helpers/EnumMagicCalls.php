<?php

namespace Henzeb\Enumhancer\Helpers;

use BadMethodCallException;
use UnitEnum;

abstract class EnumMagicCalls
{
    public static function call(UnitEnum $enum, string $name, array $arguments): mixed
    {
        EnumCheck::check($enum::class);

        if (EnumCompare::isValidCall($enum::class, $name, $arguments)) {
            return EnumCompare::call($enum, $name);
        }

        if (EnumState::isValidCall($enum::class, $name)) {
            return EnumState::call($enum, $name, $arguments);
        }

        return self::static($enum::class, $name);
    }

    public static function static(string $class, string $name): mixed
    {
        EnumCheck::check($class);

        if (EnumImplements::constructor($class)) {
            return EnumGetters::tryGet($class, $name, true) ?? self::throwException($class, $name);
        }

        self::throwException($class, $name);
    }

    public static function throwException($class, $name): never
    {
        throw new BadMethodCallException(sprintf('Call to undefined method %s::%s(...)', $class, $name));
    }
}
