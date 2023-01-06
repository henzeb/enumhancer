<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Contracts\Mapper;
use function Henzeb\Enumhancer\Functions\n;


/**
 * @method static self anotherMappedEnum()
 * @method static bool isMapped()
 */
enum EnhancedIntBackedEnum: int
{
    use Enhancers, Constructor;

    case Open = 1;
    case Close = 99;

    const ConstantEnum = self::Close;
}
