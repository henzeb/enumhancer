<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsNullEnum
{
    use Defaults, From, Makers;

    case Enum;
}
