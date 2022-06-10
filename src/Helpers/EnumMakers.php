<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use UnitEnum;
use ValueError;
use Henzeb\Enumhancer\Concerns\Mappers;


class EnumMakers
{
    private static function implementsMappers(string $enum): bool
    {
        return in_array(Mappers::class, class_uses_recursive($enum));
    }
    public static function make(string $class, int|string|null $value, bool $useMapper = false): mixed
    {
        EnumCheck::check($class);

        if($useMapper && self::implementsMappers($class)) {
            return $class::make($value);
        }

        if (null === $value) {
            throw new ValueError('Invalid value!');
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

    public static function tryMake(string $class, int|string|null $value, bool $useMapper = false): mixed
    {
        EnumCheck::check($class);

        try {
            return self::make($class, $value, $useMapper);
        } catch (ValueError) {
            return null;
        }
    }

    public static function makeArray(string $class, iterable $values, bool $useMapper = false): array
    {
        EnumCheck::check($class);
        $return = [];

        foreach($values as $value) {
            $return[] = self::make($class, $value, $useMapper);
        }

        return $return;
    }

    public static function tryMakeArray(string $class, iterable $values, bool $useMapper = false): array
    {
        EnumCheck::check($class);

        $return = [];

        foreach($values as $value) {
            $return[] = self::tryMake($class, $value, $useMapper);
        }

        return array_filter($return);
    }
}
