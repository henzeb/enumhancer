<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;
use ValueError;
use Henzeb\Enumhancer\Concerns\Makers;
use function Henzeb\Enumhancer\Functions\backingLowercase;

abstract class EnumMakers
{
    public static function make(
        string $class,
        int|string|null $value,
        bool $useMapper = false,
        bool $useDefault = false
    ): mixed {
        EnumCheck::check($class);

        if ($useMapper && EnumImplements::mappers($class)) {
            /**
             * @var $class Makers;
             */
            return $class::make($value);
        }

        $default = self::useDefaultIf($class, $value, $useDefault);

        if ($default) {
            return $default;
        }

        $match = self::match($class, $value);

        if ($match) {
            return $match;
        }

        throw new ValueError('Invalid Enum key!');
    }

    public static function tryMake(
        string $class,
        int|string|null $value,
        bool $useMapper = false,
        bool $useDefault = true
    ): mixed {
        EnumCheck::check($class);

        try {
            return self::make($class, $value, $useMapper, $useDefault);
        } catch (ValueError) {
            return $useDefault ? self::default($class) : null;
        }
    }

    public static function makeArray(string $class, iterable $values, bool $useMapper = false): array
    {
        EnumCheck::check($class);
        $return = [];

        foreach ($values as $value) {
            $return[] = self::make($class, $value, $useMapper);
        }

        return $return;
    }

    public static function tryMakeArray(string $class, iterable $values, bool $useMapper = false): array
    {
        EnumCheck::check($class);

        $return = [];

        foreach ($values as $value) {
            $return[] = self::tryMake($class, $value, $useMapper);
        }

        return array_filter($return);
    }

    private static function default(string $class): ?UnitEnum
    {
        if (EnumImplements::defaults($class) && method_exists($class, 'default')) {
            return $class::default();
        }

        return null;
    }

    public static function cast(string $class, UnitEnum|string|int $enum): mixed
    {
        EnumCheck::check($class);

        if ($enum instanceof $class) {
            return $enum;
        }
        return self::make($class, $enum, useMapper: true, useDefault: true);
    }

    private static function match(string $class, int|string|null $value): ?UnitEnum
    {
        $value = strtolower($value);
        /**
         * @var $class UnitEnum
         */
        foreach ($class::cases() as $case) {
            if ($value === strtolower($case->name)) {
                return $case;
            }

            if (strtolower(backingLowercase($case)->value) === $value) {
                return $case;
            }
        }
        return null;
    }

    /**
     * @param string $class
     * @param bool $useDefault
     * @param int|string|null $value
     * @return UnitEnum|null
     */
    protected static function useDefaultIf(string $class, int|string|null $value, bool $useDefault): ?UnitEnum
    {
        if ($value && $useDefault && is_string($value) && strtolower($value) === 'default') {
            return self::default($class);
        }
        return null;
    }
}
