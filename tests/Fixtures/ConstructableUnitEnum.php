<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\Comparison;
use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self CALLABLE()
 */
enum ConstructableUnitEnum
{
    use Constructor, Mappers, Comparison, From;

    case CALLABLE;
}
