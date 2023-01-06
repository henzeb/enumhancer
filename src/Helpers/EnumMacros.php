<?php

namespace Henzeb\Enumhancer\Helpers;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use UnitEnum;
use function is_string;
use function sprintf;
use function trigger_error;
use const E_USER_ERROR;

/**
 * @internal
 */
final class EnumMacros
{
    private static array $macros;

    public static function macro(string $enum, string $name, callable $callable): void
    {
        EnumCheck::check($enum);

        self::$macros[$enum][$name] = $callable;
    }

    /**
     * @throws ReflectionException
     */
    public static function mixin(string $enum, string|object $mixin): void
    {
        EnumCheck::check($enum);

        if (is_string($mixin)) {
            $mixin = new $mixin();
        }

        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            self::macro($enum, $method->name, $method->invoke($mixin));
        }

        unset($mixin);
    }

    public static function flush(string $enum): void
    {
        EnumCheck::check($enum);

        if (isset(self::$macros[$enum])) {
            unset(self::$macros[$enum]);
        }
    }

    public static function hasMacro(string $enum, string $name): bool
    {
        EnumCheck::check($enum);

        return EnumImplements::macros($enum) && isset(self::$macros[$enum][$name]);
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

        $macro = self::$macros[$enum::class][$name];

        if (self::isStaticMacro($macro)) {
            return self::callStatic($enum::class, $name, $arguments);
        }

        $macro = Closure::bind($macro, $enum, $enum::class);

        if (!$macro) {
            self::triggerError($enum::class, $name);
        }

        return $macro(...$arguments);
    }

    /**
     * @throws ReflectionException
     */
    public static function callStatic(string $enum, string $name, array $arguments): mixed
    {
        EnumCheck::check($enum);

        $macro = Closure::bind(self::$macros[$enum][$name], null, $enum);

        if (!$macro || false === self::isStaticMacro($macro)) {
            self::triggerError($enum, $name);
        }

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
