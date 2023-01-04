<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks;

use Henzeb\Enumhancer\Concerns\Bitmasks;

enum BitmasksIntEnum: int
{
    use Bitmasks;

    private const BIT_VALUES = true;

    case Execute = 8;
    case Read = 16;
    case Write = 32;
}
