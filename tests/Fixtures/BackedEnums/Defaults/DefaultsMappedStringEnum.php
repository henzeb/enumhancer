<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsMappedStringEnum: string
{
    use Defaults, From, Mappers;

    case Enum = 'enum';
    case DefaultEnum = 'defaultEnum';

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
