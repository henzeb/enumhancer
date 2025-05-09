<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use TypeError;
use UnitEnum;
use function is_null;

/**
 * @internal
 */
final class EnumCheck
{
    public static function check(UnitEnum|string $enum, string|null $class = null): void
    {
        if (!$enum instanceof UnitEnum && !enum_exists($enum, true)) {
            self::throwError($class);
        }
    }

    public static function matches(string $class, BackedEnum|UnitEnum|string|null ...$enums): void
    {
        foreach (array_filter($enums) as $enum) {
            if (!is_string($enum) && !is_a($enum, $class)) {
                self::throwIsNotTheSameError($class, $enum::class);
            }
        }
    }

    private static function throwError(string|null $class = null): never
    {
        $method = 'This method';

        if (!is_null($class)) {
            $method = sprintf('class `%s`', $class);
        }

        throw new TypeError(
            sprintf(
                '%s can only be used with an enum',
                $method
            )
        );
    }

    private static function throwIsNotTheSameError(string $class, string $enum): never
    {
        throw new TypeError('All enums must be a `' . $class . '`, `' . $enum . '` was given');
    }
}
