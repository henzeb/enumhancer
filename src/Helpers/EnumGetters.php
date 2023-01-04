<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Mappers;
use ReflectionClass;
use UnitEnum;
use ValueError;
use function Henzeb\Enumhancer\Functions\backing;
use function strtolower;

/**
 * @internal
 */
final class EnumGetters
{
    public static function get(
        string $class,
        int|string|UnitEnum|null $value,
        bool $useMapper = false,
        bool $useDefault = false
    ): UnitEnum {
        EnumCheck::check($class);

        $value = $value?->name ?? $value;

        if (($useDefault)
            && strtolower($value ?? '') === 'default'
        ) {
            return EnumDefaults::default($class) ?? self::throwError($value, $class);
        }

        if ($useMapper && EnumImplements::mappers($class)) {
            /**
             * @var $class Mappers|UnitEnum;
             */
            return $class::get($value);
        }

        $match = $match ?? self::match($class, $value);

        if ($match) {
            return $match;
        }

        self::throwError($value, $class);
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
            return $useDefault ? EnumDefaults::default($class) : null;
        }
    }

    public static function getArray(
        string $class,
        iterable $values,
        bool $useMapper = false
    ): array {
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

    public static function cast(string $class, UnitEnum|string|int $enum): UnitEnum
    {
        EnumCheck::check($class);

        if ($enum instanceof $class) {
            return $enum;
        }

        return self::get($class, $enum, true);
    }

    public static function tryCast(string $class, UnitEnum|int|string $key): ?UnitEnum
    {
        try {
            return self::cast($class, $key);
        } catch (ValueError) {
            return null;
        }
    }

    private static function match(UnitEnum|string $class, int|string|null $value): ?UnitEnum
    {
        if (null === $value) {
            return null;
        }

        $constants = self::cases($class);

        $value = strtolower($value);

        $case = self::findCase($constants, $value);

        return $case ?? $constants[array_keys($constants)[$value] ?? null] ?? null;
    }

    /**
     * @param UnitEnum|int|string|null $value
     * @param string $class
     * @return mixed
     */
    protected static function throwError(
        UnitEnum|int|string|null $value,
        string $class
    ) {
        throw new ValueError(
            sprintf(
                '"%s" is not a valid backing value for enum "%s"',
                $value,
                $class,
            )
        );
    }

    public static function cases(
        UnitEnum|string $class
    ): array {
        return array_filter(
            (new ReflectionClass($class))->getConstants(),
            fn($constant) => $constant instanceof $class
        );
    }

    protected static function findCase(array $constants, string $value): ?UnitEnum
    {
        foreach ($constants as $name => $case) {
            if ($value === strtolower($name)) {
                return $case;
            }

            if (strtolower(backing($case)->value) === $value) {
                return $case;
            }
        }

        return null;
    }
}
