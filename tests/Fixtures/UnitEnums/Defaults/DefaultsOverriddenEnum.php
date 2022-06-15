<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsOverriddenEnum
{
    use Defaults, From, Makers;

    case Enum;
    case DefaultEnum;

    public static function default(): ?self
    {
        return self::Enum;
    }
}
