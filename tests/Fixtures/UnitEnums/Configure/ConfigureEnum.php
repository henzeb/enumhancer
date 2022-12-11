<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Configure;

use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Configure;

enum ConfigureEnum
{
    use Enhancers, Configure;

    case Configured;
    case NotConfigured;

}
