<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use UnitEnum;
use ValueError;

class EnumMakers
{
    public static function make(string $class, int|string|null $value): mixed
    {
        EnumCheck::check($class);

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

    public static function tryMake(string $class, int|string|null $value): mixed
    {
        EnumCheck::check($class);

        try {
            return self::make($class, $value);
        } catch (ValueError) {
            return null;
        }
    }

    public static function makeArray(string $class, iterable $values): array
    {
        EnumCheck::check($class);
        $return = [];

        foreach($values as $value) {
            $return[] = self::make($class, $value);
        }

        return $return;
    }

    public static function tryMakeArray(string $class, iterable $values): array
    {
        EnumCheck::check($class);

        $return = [];

        foreach($values as $value) {
            $return[] = self::tryMake($class, $value);
        }

        return array_filter($return);
    }
}
