<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Contracts\Reporter;

enum NotReportingEnum: string
{
    use Enhancers;

    case ENUM = 'an enum';
    case ANOTHER_ENUM = 'another enum';

    protected static function reporter(): ?Reporter
    {
        return null;
    }
}
