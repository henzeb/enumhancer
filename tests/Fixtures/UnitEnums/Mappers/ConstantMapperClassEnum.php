<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers;

use Henzeb\Enumhancer\Concerns\Mappers;

enum ConstantMapperClassEnum
{
    use Mappers;

    case Alpha;
    case Beta;


    private const MAP_NOMAP = 'not a class';

    private const MAP_Class = ConstantMapperClass::class;
}
