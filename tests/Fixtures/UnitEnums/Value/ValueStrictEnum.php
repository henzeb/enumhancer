<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Value;

use Henzeb\Enumhancer\Concerns\Value;

enum ValueStrictEnum
{
    use value;
    const strict = true;
    case Strict;

    case Spades;
}
