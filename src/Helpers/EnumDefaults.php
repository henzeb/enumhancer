<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Defaults;
use ReflectionMethod;
use UnitEnum;
use function str_ends_with;

final class EnumDefaults
{
    public static function default(string $class): mixed
    {
        EnumCheck::check($class);

        if (!EnumImplements::defaults($class)) {
            return null;
        }

        return self::getConfiguredOrCustomDefault($class)
            ?? EnumGetters::tryGet($class, 'default', true, false);
    }

    public static function isDefault(UnitEnum $enum): bool
    {
        return EnumCompare::equals(
            $enum,
            self::default($enum::class)
        );
    }

    public static function hasCustomDefaultMethod(string $class): bool
    {
        $fileName = (new ReflectionMethod($class, 'default'))->getFileName() ?: '';

        return !str_contains($fileName, 'Henzeb/Enumhancer')
            && !str_ends_with($fileName, 'Defaults.php');
    }

    private static function getConfiguredOrCustomDefault(string $class): ?UnitEnum
    {
        $configured = EnumProperties::get($class, EnumProperties::reservedWord('defaults'));

        $hasCustomMethod = self::hasCustomDefaultMethod($class);

        if ($configured && !$hasCustomMethod) {
            return $configured;
        }

        if ($hasCustomMethod) {
            /**
             * @var $class UnitEnum|Defaults
             */
            return $class::default();
        }
        return null;
    }
}
