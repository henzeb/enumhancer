<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\Subset\EnumSubsetMethods;

/**
 * @method static array cases()
 */
trait Dropdown
{
    public static function dropdown(bool $keepEnumCase = false): array
    {
        EnumCheck::check(self::class);

        return (new EnumSubsetMethods(
            self::class,
            ...self::cases()
        ))->dropdown($keepEnumCase);
    }
}
