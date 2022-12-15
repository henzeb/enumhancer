<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Contracts\Mapper;

class EnumArrayMapper extends Mapper
{
    public function __construct(private array $mappable)
    {
    }
    protected function mappable(): array
    {
        return $this->mappable;
    }
}
