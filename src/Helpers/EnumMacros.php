<?php

namespace Henzeb\Enumhancer\Helpers;

use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use UnitEnum;
use function array_change_key_case;
use function array_merge;
use function is_null;
use function is_string;
use function sprintf;
use function trigger_error;
use const E_USER_ERROR;

/**
 * @internal
 */
final class EnumMacros
{
    private static array $macros = [];
    private static array $globalMacros = [];

    public static function macro(string $enum, string $name, callable $callable): void
    {
        EnumCheck::check($enum);

        self::$macros[$enum][$name] = $callable;
    }

    public static function globalMacro(string $name, callable $callable): void
    {
        self::$globalMacros[$name] = $callable;
    }

    private static function getMethodsFromMixin(object $mixin): array
    {
        return (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );
    }

    /**
     * @throws ReflectionException
     */
    public static function mixin(?string $enum, string|object $mixin): void
    {
        if (!is_null($enum)) {
            EnumCheck::check($enum);
        }

        if (is_string($mixin)) {
            $mixin = new $mixin();
        }


        foreach (self::getMethodsFromMixin($mixin) as $method) {
            if ($enum) {
                self::macro($enum, $method->name, $method->invoke($mixin));
                continue;
            }

            self::globalMacro($method->name, $method->invoke($mixin));
        }

        unset($mixin);
    }

    public static function globalMixin(string|object $mixin): void
    {
        self::mixin(null, $mixin);
    }

    public static function flush(string $enum): void
    {
        EnumCheck::check($enum);

        if (isset(self::$macros[$enum])) {
            unset(self::$macros[$enum]);
        }
    }

    public static function flushGlobal(): void
    {
        self::$globalMacros = [];
    }

    public static function hasMacro(string $enum, string $name): bool
    {
        return self::getMacro($enum, $name) !== null;
    }

    private static function getMacro(string $enum, string $name): ?callable
    {
        EnumCheck::check($enum);

        $name = strtolower($name);

        return array_change_key_case(self::getMacros($enum))[$name] ?? null;
    }

    private static function getMacros(string $enum): array
    {
        return array_merge(
            self::$globalMacros ?? [],
            self::$macros[$enum] ?? []
        );
    }

    /**
     * @throws ReflectionException
     */
    private static function isStaticMacro(callable $callable): bool
    {
        return (new ReflectionFunction($callable(...)))->isStatic();
    }

    /**
     * @throws ReflectionException
     */
    public static function call(UnitEnum $enum, string $name, array $arguments): mixed
    {
        EnumCheck::check($enum);

        $macro = self::getMacro($enum::class, $name);

        if ($macro && self::isStaticMacro($macro)) {
            return self::callStatic($enum::class, $name, $arguments);
        }

        $macro = ($macro ?? fn() => true)(...)->bindTo($enum, $enum::class);

        return $macro(...$arguments);
    }

    /**
     * @throws ReflectionException
     */
    public static function callStatic(string $enum, string $name, array $arguments): mixed
    {
        EnumCheck::check($enum);

        $macro = self::getMacro($enum, $name);

        if (!$macro || false === self::isStaticMacro($macro)) {
            self::triggerError($enum, $name);
        }

        $macro = $macro(...)->bindTo(null, $enum);

        return $macro(...$arguments);
    }

    private static function triggerError(string $enum, string $name): never
    {
        trigger_error(
            sprintf(
                'Uncaught Error: Non-static method %s::%s() cannot be called statically',
                $enum,
                $name
            ),
            E_USER_ERROR
        );
    }
}
