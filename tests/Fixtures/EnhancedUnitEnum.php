<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Contracts\Mapper;

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
