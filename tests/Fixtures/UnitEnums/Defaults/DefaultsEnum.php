<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\ConfigureDefaults;

enum DefaultsEnum
{
    use Defaults, From, Getters, ConfigureDefaults;

    case Enum;
    case Default;
}
