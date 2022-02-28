<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Helpers\MultiEnumMethods;

trait Comparison
{
    /**
     * @mixin BackedEnum
     */
    final public function equals(self|string ...$equals): bool
    {
        return (new MultiEnumMethods(self::class, $this))
            ->equals(...$equals);
    }

}
