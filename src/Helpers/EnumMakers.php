<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use UnitEnum;
use ValueError;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\Defaults;


abstract class EnumMakers
{
    private static function implementsMappers(string $enum): bool
    {
        return in_array(Mappers::class, class_uses_recursive($enum));
    }

    private static function implementsDefaulting(string $enum): bool
    {
        return in_array(Defaults::class, class_uses_recursive($enum));
    }

    public static function make(string $class, int|string|null $value, bool $useMapper = false, bool $useDefault = false): mixed
    {
        EnumCheck::check($class);

        if (null === $value) {
            throw new ValueError('Invalid value!');
        }

        if ($useMapper && self::implementsMappers($class) && method_exists($class, 'make')) {
            return $class::make($value);
        }

        if($useDefault && strtolower($value)==='default' && $default = self::default($class)) {
            return $default;
        }

        $isBackedEnum = is_subclass_of($class, BackedEnum::class);

        /**
         * @var $class UnitEnum|static
         */
        foreach ($class::cases() as $case) {

            if (strtoupper($value) === strtoupper($case->name)) {
                return $case;
            }

            if ($isBackedEnum && strtolower($value) === strtolower((string)$case->value)) {
                return $case;
            }
        }

        throw new ValueError('Invalid Enum key!');
    }

    public static function tryMake(
        string $class,
        int|string|null $value,
        bool $useMapper = false,
        bool $useDefault = true,
    ): mixed
    {
        EnumCheck::check($class);

        try {
            return self::make($class, $value, $useMapper, $useDefault);
        } catch (ValueError) {
            return $useDefault?self::default($class):null;
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
        if (self::implementsDefaulting($class) && method_exists($class, 'default')) {
            return $class::default();
        }

        return null;
    }

    public static function cast(string $class, UnitEnum|string|int $enum): mixed
    {
        EnumCheck::check($class);
        if($enum instanceof $class) {
            return $enum;
        }
        return self::make($class, $enum, useMapper: true, useDefault: true);
    }
}
