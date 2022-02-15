<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self 0()
 */
enum StringBackedStaticCallableEnum: string
{
    use Constructor;

    case CALLABLE = 'gets_callable';
}
