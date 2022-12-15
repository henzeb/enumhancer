<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults;

use Henzeb\Enumhancer\Concerns\ConfigureDefaults;
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Getters;

enum DefaultsConstantEnum
{
    use Defaults, From, Getters, ConfigureDefaults;

    case Enum;

    case DefaultEnum;
    private const Default = self::DefaultEnum;
}
