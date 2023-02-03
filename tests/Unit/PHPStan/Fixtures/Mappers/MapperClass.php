<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers;

use Henzeb\Enumhancer\Contracts\Mapper;

class MapperClass extends Mapper
{
    protected function mappable(): array
    {
        return [];
    }
}
