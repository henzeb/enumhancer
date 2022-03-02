<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Helpers\EnumSubsetMethods;

trait Comparison
{
    /**
     * @mixin BackedEnum
     */
    final public function equals(self|string ...$equals): bool
    {
        return (new EnumSubsetMethods(self::class, $this))
            ->equals(...$equals);
    }

}
