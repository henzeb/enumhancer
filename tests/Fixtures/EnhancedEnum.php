<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Contracts\Mapper;

/**
 * @method static self anotherMappedEnum()
 */
enum EnhancedEnum: string
{
    use Enhancers, Constructor;

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
