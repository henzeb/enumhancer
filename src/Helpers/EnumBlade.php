<?php

namespace Henzeb\Enumhancer\Helpers;

use Blade;
use Henzeb\Enumhancer\Exceptions\NotAnEnumException;
use UnitEnum;
use function Henzeb\Enumhancer\Functions\value as value;

/**
 * @internal
 */
final class EnumBlade
{
    /**
     * @throws NotAnEnumException
     */
    public static function register(string ...$enumclasses): void
    {
        foreach ($enumclasses as $enumClass) {
            self::add($enumClass, true);
        }
    }

    /**
     * @throws NotAnEnumException
     */
    public static function registerLowercase(string ...$enumclasses): void
    {
        foreach ($enumclasses as $enumClass) {
            self::add($enumClass, false);
        }
    }

    /**
     * @throws NotAnEnumException
     */
    private static function add(string $enumClass, bool $keepValueCase): void
    {
        if (!is_subclass_of($enumClass, UnitEnum::class, true)) {
            throw new NotAnEnumException($enumClass);
        }

        Blade::stringable(
            $enumClass,
            function (UnitEnum $enum) use ($keepValueCase): string {
                return value($enum, $keepValueCase);
            }
        );
    }
}
