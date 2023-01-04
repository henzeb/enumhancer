<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers;

use Henzeb\Enumhancer\Concerns\Mappers;

enum ConstantMapperClassFlippedEnum
{
    use Mappers;

    case Alpha;
    case Beta;
    private const MAP_FLIPPED = ConstantMapperClass::class;
}
