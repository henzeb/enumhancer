<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsIntEnum: int
{
    use Defaults, From, Makers;

    case Enum = 1;
    case Default = 0;
}
