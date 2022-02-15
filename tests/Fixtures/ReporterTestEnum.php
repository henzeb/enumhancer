<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Reporters;

enum ReporterTestEnum: string
{
    use Reporters;

    case ENUM = 'an enum';
    case ANOTHER_ENUM = 'another enum';
}
