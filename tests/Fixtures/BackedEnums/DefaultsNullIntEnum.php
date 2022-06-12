<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsNullIntEnum: int
{
    use Defaults, From, Makers;

    case Enum = 1;
}
