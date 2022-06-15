<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Defaults;

use Henzeb\Enumhancer\Concerns\From;
use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Defaults;

enum DefaultsNullStringEnum: string
{
    use Defaults, From, Makers;

    case Enum = 'enum';
}
