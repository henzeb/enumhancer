<?php

namespace Henzeb\Enumhancer\Helpers\Mappers;

use Henzeb\Enumhancer\Contracts\Mapper;
use UnitEnum;

final class EnumArrayMapper extends Mapper
{
    /**
     * @param array<string|int,string|int|UnitEnum|array<string|int,string|int|UnitEnum>> $mappable
     */
    public function __construct(private readonly array $mappable)
    {
    }


    protected function mappable(): array
    {
        return $this->mappable;
    }
}
