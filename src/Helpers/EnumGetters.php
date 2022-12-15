<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Getters;
use Henzeb\Enumhancer\Concerns\Mappers;
use ReflectionClass;
use UnitEnum;
use ValueError;
use function Henzeb\Enumhancer\Functions\backingLowercase;

abstract class EnumGetters
{
    public static function get(
        string $class,
        int|string|UnitEnum|null $value,
        bool $useMapper = false,
        bool $useDefault = false
    ): UnitEnum {
        EnumCheck::check($class);

        if ($useMapper && EnumImplements::mappers($class)) {
            /**
             * @var $class Mappers;
             */
            return $class::get($value);
        }

        $value = $value?->name ?? $value;

        $default = self::useDefaultIf($class, $value, $useDefault);

        if ($default) {
            return $default;
        }

        $match = self::match($class, $value);

        if ($match) {
            return $match;
        }

        throw new ValueError(
            sprintf(
                '"%s" is not a valid backing value for enum "%s"',
                $value,
                $class,
            )
        );
    }

    public static function tryGet(
        string $class,
        int|string|UnitEnum|null $value,
        bool $useMapper = false,
        bool $useDefault = true
    ): ?UnitEnum {
        EnumCheck::check($class);

        try {
            return self::get($class, $value, $useMapper, $useDefault);
        } catch (ValueError) {
            return $useDefault ? self::default($class) : null;
        }
    }

    public static function getArray(string $class, iterable $values, bool $useMapper = false): array
    {
        EnumCheck::check($class);
        $return = [];

        foreach ($values as $value) {
            $return[] = self::get($class, $value, $useMapper);
        }

        return $return;
    }

    public static function tryArray(string $class, iterable $values, bool $useMapper = false): array
    {
        EnumCheck::check($class);

        $return = [];

        foreach ($values as $value) {
            $return[] = self::tryGet($class, $value, $useMapper);
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

    public static function cast(string $class, UnitEnum|string|int $enum): UnitEnum
    {
        EnumCheck::check($class);

        if ($enum instanceof $class) {
            return $enum;
        }

        return self::get($class, $enum, useMapper: true, useDefault: true);
    }

    public static function tryCast(string $class, UnitEnum|int|string $key): ?UnitEnum
    {
        try {
            return self::cast($class, $key);
        } catch (ValueError) {
            return null;
        }
    }

    private static function match(string $class, int|string|null $value): ?UnitEnum
    {
        if (null === $value) {
            return null;
        }

        /**
         * @var $class UnitEnum
         */
        if (isset($class::cases()[$value])) {
            return $class::cases()[$value];
        }

        if (defined($class . '::' . $value) && constant($class . '::' . $value) instanceof $class) {
            return constant($class . '::' . $value);
        }

        return self::findInCases($class, $value) ?? self::findInConstants($class, $value);
    }

    private static function findInCases(string $class, int|string $value): ?UnitEnum
    {
        /**
         * @var $class UnitEnum
         */
        $value = strtolower($value);

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
        if ($useDefault && is_string($value) && strtolower($value) === 'default') {
            return self::default($class);
        }
        return null;
    }

    private static function findInConstants(UnitEnum|string $class, int|string $value): ?UnitEnum
    {
        $constants = (new ReflectionClass($class))->getConstants();

        foreach ($constants as $name => $constant) {
            if ($constant instanceof $class && strtolower($name) === strtolower($value)) {
                return $constant;
            }
        }

        return null;
    }
}
