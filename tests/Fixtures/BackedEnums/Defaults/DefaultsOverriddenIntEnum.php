<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsOverriddenIntEnum: int
{
    use Defaults, From, Makers;

    case Enum = 1;
    case Default = 0;

    public static function default(): ?self
    {
        return self::Enum;
    }
}
