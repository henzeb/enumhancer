<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Getters;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;
use Henzeb\Enumhancer\Concerns\Makers;

enum GetUnitEnum
{
    use Makers, Getters, From;

    case Zero;
    case One;
    case Three;
}
