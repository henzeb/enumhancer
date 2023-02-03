<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers;

enum SimpleEnumWithMapperConstant
{
    private const MAP_SELF = ['test' => self::Hearts];

    case Hearts;

}
