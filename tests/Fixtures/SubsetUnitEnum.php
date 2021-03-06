<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Concerns\Subset;
use Henzeb\Enumhancer\Concerns\Extractor;
use Henzeb\Enumhancer\Concerns\Comparison;

enum SubsetUnitEnum
{
    use Subset, Extractor, Comparison, State;

    case ENUM;
    case ANOTHER_ENUM;
    case THIRD_ENUM;
}
