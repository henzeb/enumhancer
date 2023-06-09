<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks;

use Henzeb\Enumhancer\Concerns\Bitmasks;

enum BitmasksCorrectModifierEnum: int
{
    use Bitmasks;

    const BIT_VALUES = true;

    const BIT_MODIFIER = true;

    case Neither = 1;
    case Read = 2;
    case Write = 4;
    case Both = 6;
}
