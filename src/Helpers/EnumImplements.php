<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\Defaults;

abstract class EnumImplements
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

    private static function classUsesTrait(string $class)
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
