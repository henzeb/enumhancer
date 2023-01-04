<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Bitmasks;
use Henzeb\Enumhancer\Concerns\Comparison;
use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\Labels;
use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\State;

/**
 * @internal
 */
final class EnumImplements
{
    public static function traitOn(string $class, string $implements): bool
    {
        EnumCheck::check($class);

        return in_array($implements, self::classUsesTrait($class));
    }

    public static function mappers(string $class): bool
    {
        return self::traitOn($class, Mappers::class);
    }

    public static function defaults(string $class): bool
    {
        return self::traitOn($class, Defaults::class);
    }

    public static function state(string $class): bool
    {
        return self::traitOn($class, State::class);
    }

    public static function labels(string $class): bool
    {
        return self::traitOn($class, Labels::class);
    }

    public static function macros(string $class): bool
    {
        return self::traitOn($class, Macros::class);
    }

    public static function constructor(string $class): bool
    {
        return self::traitOn($class, Constructor::class);
    }

    public static function comparison(string $class): bool
    {
        return self::traitOn($class, Comparison::class);
    }

    public static function bitmasks(string $class): bool
    {
        return self::traitOn($class, Bitmasks::class);
    }

    private static function classUsesTrait(string $class): array
    {
        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += self::traitUsesTrait($class);
        }

        return array_unique($results);
    }

    private static function traitUsesTrait(string $trait): array
    {
        $traits = class_uses($trait) ?: [];

        foreach ($traits as $trait) {
            $traits += self::traitUsesTrait($trait);
        }

        return $traits;
    }
}
