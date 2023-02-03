<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Contracts\Reporter;

abstract class Enumhancer
{
    public static function macro(string $name, callable $callable): void
    {
        EnumMacros::globalMacro($name, $callable);
    }

    public static function mixin(string|object $mixin): void
    {
        EnumMacros::globalMixin($mixin);
    }

    public static function flushMacros(): void
    {
        EnumMacros::flushGlobal();
    }

    public static function setReporter(Reporter|string|null $reporter): void
    {
        EnumReporter::set($reporter);
    }

    public static function property(string $key, mixed $value = null): mixed
    {
        if (null === $value) {
            return EnumProperties::getGlobal($key);
        }

        return EnumProperties::global($key, $value);
    }

    public static function unsetProperty(string $key): void
    {
        EnumProperties::clearGlobal($key);
    }

    public static function clearProperties(): void
    {
        EnumProperties::clearGlobal();
    }
}
