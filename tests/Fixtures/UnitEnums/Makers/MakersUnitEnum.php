<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Makers;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;

enum MakersUnitEnum
{
    use Makers, From;

    case Zero;
    case One;
    case Three;
}
