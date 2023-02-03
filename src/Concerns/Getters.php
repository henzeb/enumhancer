<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumGetters;

trait Getters
{
    public static function get(int|string|null $value): self
    {
        return EnumGetters::get(self::class, $value);
    }

    public static function tryGet(int|string|null $value, bool $useDefault = true): ?self
    {
        return EnumGetters::tryGet(self::class, $value, false, $useDefault);
    }

    /**
     * @param iterable $values
     * @return self[]
     */
    public static function getArray(iterable $values): array
    {
        return EnumGetters::getArray(self::class, $values);
    }

    /**
     * @param iterable $values
     * @return self[]
     */
    public static function tryArray(iterable $values, bool $useDefault = true): array
    {
        return EnumGetters::tryArray(self::class, $values, false, $useDefault);
    }
}
