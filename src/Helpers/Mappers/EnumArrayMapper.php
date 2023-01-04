<?php

namespace Henzeb\Enumhancer\Helpers\Mappers;

use Henzeb\Enumhancer\Contracts\Mapper;

final class EnumArrayMapper extends Mapper
{
    public function __construct(private array $mappable)
    {
    }

    protected function mappable(): array
    {
        return $this->mappable;
    }
}
