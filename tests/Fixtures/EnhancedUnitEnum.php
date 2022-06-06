<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self anotherMappedEnum()
 * @method isAnother_Enum()
 * @method isEnum()
 * @method isNotEnum()
 */
enum EnhancedUnitEnum
{
    use Enhancers, Constructor;

    case ENUM;
    case ANOTHER_ENUM;
    case THIRD_ENUM;
}
