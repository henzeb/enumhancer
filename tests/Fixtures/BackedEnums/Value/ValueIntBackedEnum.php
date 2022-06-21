<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Value;

use Henzeb\Enumhancer\Concerns\Value;

enum ValueIntBackedEnum: int
{
    use Value;

    case ENUM = 64;
    case ANOTHER_ENUM = 128;
}
