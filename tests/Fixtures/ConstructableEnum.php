<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Comparison;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self CALLABLE()
 */
enum ConstructableEnum
{
    use Constructor, Mappers, Comparison;

    case CALLABLE;
}
