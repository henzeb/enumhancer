<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsMappedIntEnum: int
{
    use Defaults, From, Mappers;

    case Enum = 1;
    case DefaultEnum = 0;

    protected static function mapper(): ?Mapper
    {
        return new class extends Mapper {
            protected function mappable(): array
            {
                return [
                    'default' => DefaultsMappedStringEnum::Enum
                ];
            }
        };
    }
}
