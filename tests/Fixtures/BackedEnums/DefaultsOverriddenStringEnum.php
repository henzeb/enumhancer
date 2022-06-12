<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsOverriddenStringEnum: string
{
    use Defaults, From, Makers;

    case Enum = 'enum';
    case Default = 'default';

    public static function default(): ?self
    {
        return self::Enum;
    }
}
