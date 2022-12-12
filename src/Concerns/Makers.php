<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumGetters;

/**
 * @deprecated
 */
trait Makers
{
    use Getters;
    /**
     * @deprecated
     */
    public static function make(int|string|null $value): self
    {
        return self::get($value);
    }

    /**
     * @deprecated
     */
    public static function tryMake(int|string|null $value): ?self
    {
        return self::tryGet($value);
    }

    /**
     * @param iterable $values
     * @return self[]
     * @deprecated
     */
    public static function makeArray(iterable $values): array
    {
        return self::getArray($values);
    }

    /**
     * @param iterable $values
     * @return self[]
     * @deprecated
     */
    public static function tryMakeArray(iterable $values): array
    {
        return self::tryArray($values);
    }
}
