<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Configure;
use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Extractor;
use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Reporters;

/**
 * @method static bool bitmasks(string $enum)
 * @method static bool comparison(string $enum)
 * @method static bool defaults(string $enum)
 * @method static bool dropdown(string $enum)
 * @method static bool from(string $enum)
 * @method static bool labels(string $enum)
 * @method static bool mappers(string $enum)
 * @method static bool properties(string $enum)
 * @method static bool state(string $enum)
 * @method static bool subset(string $enum)
 * @method static bool value(string $enum)
 * @method static bool configure(string $enum)
 * @method static bool constructor(string $enum)
 * @method static bool extractor(string $enum)
 * @method static bool macros(string $enum)
 * @method static bool reporters(string $enum)
 * @method static bool configureDefaults(string $enum)
 * @method static bool configureLabels(string $enum)
 * @method static bool configureMapper(string $enum)
 * @method static bool configureState(string $enum)
 */
final class EnumImplements
{
    private static array $passivelyImplements = [
        Configure::class,
        Constructor::class,
        Extractor::class,
        Macros::class,
        Reporters::class,
    ];

    private static ?array $available = null;

    public static function available(): array
    {
        return self::$available ?? self::buildAvailable();
    }

    private static function buildAvailable(): array
    {
        $availablePassive = array_map(self::getTraitsFrom(...), self::$passivelyImplements);

        $available = array_values(array_merge(
            array_values(self::getTraitsFrom(Enhancers::class)),
            self::$passivelyImplements,
            ... $availablePassive
        ));

        $available = array_map(
            fn($class) => [strtolower(basename(str_replace('\\', '/', $class))) => $class],
            $available
        );

        return self::$available = array_merge(...$available);
    }

    public static function enumhancer(string $class): bool
    {
        if (self::implements($class, Enhancers::class)) {
            return true;
        }

        foreach (self::available() as $trait) {
            if (self::implements($class, $trait)) {
                return true;
            }
        }
        return false;
    }

    public static function __callStatic(string $name, array $arguments): bool
    {
        $implements = self::available()[strtolower($name)] ?? null;

        if ($implements && $arguments[0] && is_string($arguments[0])) {
            return self::implements($arguments[0], $implements);
        }

        trigger_error(
            sprintf('Call to undefined method %s::%s()', self::class, $name),
            E_USER_ERROR
        );
    }

    public static function implements(string $class, string $implements): bool
    {
        EnumCheck::check($class);

        return in_array($implements, self::classUsesTrait($class));
    }

    private static function classUsesTrait(string $class): array
    {
        $results = [];

        foreach (array_reverse(class_parents($class) ?: []) + [$class => $class] as $class) {
            $results += self::getTraitsFrom($class);
        }

        return array_unique($results);
    }

    private static function getTraitsFrom(string $class): array
    {
        $traits = class_uses($class) ?: [];

        foreach ($traits as $class) {
            $traits += self::getTraitsFrom($class);
        }

        return $traits;
    }
}
