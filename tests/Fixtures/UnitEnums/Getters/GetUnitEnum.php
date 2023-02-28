<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Getters;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;

enum GetUnitEnum
{
    use Getters, From;

    case Zero;
    case One;
    case Three;
}
