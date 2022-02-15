<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Enhancers;

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
}
