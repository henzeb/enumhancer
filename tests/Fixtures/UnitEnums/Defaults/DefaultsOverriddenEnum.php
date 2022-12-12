<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults;

use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;

enum DefaultsOverriddenEnum
{
    use Defaults, From, Getters;

    case Enum;
    case DefaultEnum;

    public static function default(): ?self
    {
        return self::Enum;
    }
}
