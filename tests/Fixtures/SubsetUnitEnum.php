<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Subset;

enum SubsetUnitEnum
{
    use Subset;

    case ENUM;
    case ANOTHER_ENUM;
    case THIRD_ENUM;
}
