<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\From;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;

enum FromWithMappersEnum
{
    use From, Mappers;

    case NotTranslated;

    protected static function mapper(): ?Mapper
    {
        return new class extends Mapper {

            protected function mappable(): array
            {
                return [
                    'translated' => FromWithMappersEnum::NotTranslated
                ];
            }
        };
    }
}
