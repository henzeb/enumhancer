<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self CALLABLE()
 * @method static self gets_callable()
 */
enum IntBackedStaticCallableEnum: int
{
    use Constructor;

    case CALLABLE = 0;
}
