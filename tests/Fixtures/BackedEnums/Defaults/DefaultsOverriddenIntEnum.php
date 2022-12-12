<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults;

use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;

enum DefaultsOverriddenIntEnum: int
{
    use Defaults, From, Getters;

    case Enum = 1;
    case Default = 0;

    public static function default(): ?self
    {
        return self::Enum;
    }
}
