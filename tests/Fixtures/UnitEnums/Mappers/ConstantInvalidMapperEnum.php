<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers;

use Henzeb\Enumhancer\Concerns\Mappers;

enum ConstantInvalidMapperEnum
{
    use Mappers;

    case Alpha;
    case Beta;

    private const MAP = self::class;
}
