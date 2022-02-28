<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\MultiEnum;
use Henzeb\Enumhancer\Helpers\MultiEnumMethods;

trait Multi
{
    final public static function of(self ...$enums): MultiEnum
    {
        return new MultiEnumMethods(self::class, ...($enums ?:self::cases()));
    }
}
