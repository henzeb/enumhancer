<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Contracts\Mapper;

enum EnhancedEnum: string
{
    use Enhancers;

    case ENUM = 'an enum';
    case ANOTHER_ENUM = 'another enum';

    protected function labels(): array
    {
        return [
            'ENUM'=>'My label'
        ];
    }

    protected static function mapper(): ?Mapper
    {
        return new class extends Mapper
        {

            protected function mappable(): array
            {
                return [
                    'anotherMappedEnum' => EnhancedEnum::ENUM
                ];
            }
        };
    }
}
