<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Helpers\Bitmasks\EnumBitmasks;

trait Bitmasks
{
    public function bit(): int
    {
        return EnumBitmasks::getBit($this);
    }

    public static function bits(): array
    {
        return EnumBitmasks::getCaseBits(self::class);
    }

    public static function mask(self|string|int ...$enums): Bitmask
    {
        return EnumBitmasks::getMask(self::class, ...$enums);
    }

    public static function fromMask(int $mask): Bitmask
    {
        return EnumBitmasks::fromMask(self::class, $mask);
    }

    public static function tryMask(
        ?int $mask,
        BitMask|self|string|int|null ...$enums
    ): Bitmask {
        return EnumBitmasks::tryMask(
            self::class,
            $mask,
            ...$enums
        );
    }
}
