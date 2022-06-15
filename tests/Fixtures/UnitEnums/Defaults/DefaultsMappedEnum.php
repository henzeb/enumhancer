<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsMappedEnum
{
    use Defaults, From, Mappers;

    case Enum;
    case DefaultEnum;

    protected static function mapper(): ?Mapper
    {
        return new class extends Mapper {
            protected function mappable(): array
            {
                return [
                    'default' => DefaultsMappedEnum::Enum
                ];
            }
        };
    }
}
