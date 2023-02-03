<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers;

use Henzeb\Enumhancer\Concerns\Mappers;

enum EnumWithMapperClass
{
    use Mappers;

    const MAP_TEST = MapperClass::class;

    case Hearts;
}
