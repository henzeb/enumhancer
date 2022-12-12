<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults;

use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;

enum DefaultsOverriddenStringEnum: string
{
    use Defaults, From, Getters;

    case Enum = 'enum';
    case Default = 'default';

    public static function default(): ?self
    {
        return self::Enum;
    }
}
