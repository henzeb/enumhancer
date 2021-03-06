<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMakers;

trait Makers
{
    public static function make(int|string|null $value): self
    {
        return EnumMakers::make(self::class, $value);
    }

    public static function tryMake(int|string|null $value): ?self
    {
        return EnumMakers::tryMake(self::class, $value);
    }

    /**
     * @param iterable $values
     * @return self[]
     */
    public static function makeArray(iterable $values): array
    {
        return EnumMakers::makeArray(self::class, $values);
    }

    /**
     * @param iterable $values
     * @return self[]
     */
    public static function tryMakeArray(iterable $values): array
    {
        return EnumMakers::tryMakeArray(self::class, $values);
    }
}
