<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults;

use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;

enum DefaultsNullEnum
{
    use Defaults, From, Getters;

    case Enum;
}
