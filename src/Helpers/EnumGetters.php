<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use Henzeb\Enumhancer\Concerns\Mappers;
use ReflectionClass;
use UnitEnum;
use ValueError;
use function array_key_exists;
use function Henzeb\Enumhancer\Functions\backing;
use function is_object;
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
    ): mixed {
        EnumCheck::check($class);

        $value = $value->name ?? $value;

        if (($useDefault)
            && is_string($value)
            && strtolower($value) === 'default'
        ) {
            return EnumDefaults::default($class) ?? self::throwError($value, $class);
        }

        if ($useMapper && EnumImplements::mappers($class)) {
            /**
             * @var $class Mappers|UnitEnum;
             */
            return $class::get($value);
        }

        $value = is_object($value) ? $value->name : $value;

        $match = !is_null($value) ? self::match($class, $value) : null;

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
    ): mixed {
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

    public static function tryArray(
        string $class,
        iterable $values,
        bool $useMapper = false,
        bool $useDefault = true
    ): array {
        EnumCheck::check($class);

        $return = [];

        foreach ($values as $value) {
            $return[] = self::tryGet($class, $value, $useMapper, $useDefault);
        }

        return array_filter($return);
    }

    public static function cast(string|UnitEnum $class, UnitEnum|string|int $enum): mixed
    {
        EnumCheck::check($class);

        if ($enum instanceof $class) {
            return $enum;
        }

        return self::get(is_object($class) ? $class::class : $class, $enum, true);
    }

    public static function tryCast(string|UnitEnum $class, UnitEnum|int|string $key): mixed
    {
        try {
            return self::cast($class, $key);
        } catch (ValueError) {
            return null;
        }
    }

    private static function match(UnitEnum|string $class, int|string $value): ?UnitEnum
    {
        $constants = self::cases($class);

        $case = self::findCase($constants, $value);

        if (!$case && is_a($class, BackedEnum::class, true)) {
            foreach ($constants as $constant) {
                if ($constant->value == $value) {
                    $case = $constant;
                    break;
                }
            }
        }

        if ($case) {
            return $case;
        }

        if (array_key_exists($value, array_keys($constants))) {
            return $constants[array_keys($constants)[$value]];
        }

        return null;
    }

    protected static function throwError(
        UnitEnum|int|string|null $value,
        string $class
    ): never {
        throw new ValueError(
            sprintf(
                '"%s" is not a valid backing value for enum "%s"',
                is_object($value) ? $value->name : $value,
                $class
            )
        );
    }

    public static function cases(
        UnitEnum|string $class
    ): array {

        /**
         * @var class-string $class
         */

        return array_filter(
            (new ReflectionClass($class))->getConstants(),
            fn($constant) => $constant instanceof $class
        );
    }

    protected static function findCase(array $constants, int|string $value): ?UnitEnum
    {
        if (is_string($value)) {
            $value = strtolower($value);
        }

        foreach ($constants as $name => $case) {
            if (self::isCase($case, $name, $value)) {
                return $case;
            }
        }

        return null;
    }

    private static function isCase(mixed $case, string $name, int|string $value): bool
    {
        return $value === strtolower($name)
            || strtolower(backing($case) ?? '') === $value;
    }
}
