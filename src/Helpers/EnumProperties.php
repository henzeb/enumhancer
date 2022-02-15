<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Contracts\Reporter;

class EnumProperties
{
    private static array $global = [];
    private static array $properties = [];
    private static Reporter|string|null $reporter = null;


    public static function store(string $class, string $property, mixed $value): void
    {
        EnumCheck::check($class);

        self::$properties[$class][$property] = $value;
    }

    public static function get(string $class, string $property): mixed
    {
        EnumCheck::check($class);

        return self::$properties[$class][$property] ?? self::$global[$property] ?? null;
    }

    public static function global(string $key, mixed $value): void
    {
        self::$global[$key] = $value;
    }

    public static function clear(string $class, string $property = null): void
    {
        EnumCheck::check($class);

        if (!empty($property)) {
            unset(self::$properties[$class][$property]);
        }

        if (empty($property)) {
            unset(self::$properties[$class]);
        }
    }

    public static function clearGlobal(): void
    {
        self::$global = [];
    }
}
