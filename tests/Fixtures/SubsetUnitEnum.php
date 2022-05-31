<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Subset;
use Henzeb\Enumhancer\Concerns\Extractor;

enum SubsetUnitEnum
{
    use Subset, Extractor;

    case ENUM;
    case ANOTHER_ENUM;
    case THIRD_ENUM;
}
