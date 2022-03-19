<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self anotherMappedEnum()
 */
enum EnhancedUnitEnum
{
    use Enhancers, Constructor;

    case ENUM;
    case ANOTHER_ENUM;
    case THIRD_ENUM;
}
