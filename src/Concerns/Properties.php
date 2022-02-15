<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumProperties;

trait Properties
{
    final public static function property(string $property, mixed $value = null): mixed
    {
        if(null === $value) {
           return EnumProperties::get(self::class, $property);
        }

        EnumProperties::store(self::class, $property, $value);
        return $value;
    }

    final public static function unset(string $property): void
    {
        EnumProperties::clear(self::class, $property);
    }

    final public static function unsetAll(): void
    {
        EnumProperties::clear(self::class);
    }
}
