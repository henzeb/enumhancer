<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks;

use Henzeb\Enumhancer\Concerns\Bitmasks;

enum BitmasksIncorrectIntEnum: int
{
    use Bitmasks;

    private const BIT_VALUES = true;

    case Execute = 7;
    case Read = 16;
    case Write = 32;
}
