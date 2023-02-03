<?php

namespace Henzeb\Enumhancer\Helpers\Concerns;

use Henzeb\Enumhancer\Concerns\Configure;
use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Extractor;
use Henzeb\Enumhancer\Concerns\Macros;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Reporters;
use Henzeb\Enumhancer\Helpers\EnumCheck;

trait EnumImplements
{
    private static array $passivelyImplements = [
        Configure::class,
        Constructor::class,
        Extractor::class,
        Macros::class,
        Makers::class,
        Reporters::class,
    ];

    private static ?array $available = null;

    public static function available(): array
    {
        return self::$available ?? self::$available = array_merge(
            self::getTraitsFrom(Enhancers::class),
            ... array_map(self::getTraitsFrom(...), self::$passivelyImplements),
        );
    }

    public static function enumhancer(string $class): bool
    {
        EnumCheck::check($class);

        $available = self::available();

        foreach (self::getTraitsFrom($class) as $trait) {
            if (in_array($trait, $available)) {
                return true;
            }
        }

        return false;
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
