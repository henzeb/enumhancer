<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers;

use Henzeb\Enumhancer\Contracts\Mapper;

class ConstantMapperClass extends Mapper
{
    protected function mappable(): array
    {
        return [
            'alpha' => 'beta',
        ];
    }
}
