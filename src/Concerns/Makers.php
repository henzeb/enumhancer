<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumMakers;

trait Makers
{
    final public static function make(int|string|null $value): static
    {
        return EnumMakers::make(self::class, $value);
    }

    final public static function tryMake(int|string|null $value): ?static
    {
        return EnumMakers::tryMake(self::class, $value);
    }

    final public static function makeArray(iterable $values): array
    {
        return EnumMakers::makeArray(self::class, $values);
    }

    final public static function tryMakeArray(iterable $values): array
    {
        return EnumMakers::tryMakeArray(self::class, $values);
    }
}
