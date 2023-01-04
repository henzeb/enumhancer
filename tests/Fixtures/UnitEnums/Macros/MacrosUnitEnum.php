<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros;

use Henzeb\Enumhancer\Concerns\Macros;

enum MacrosUnitEnum
{
    use Macros;

    case Hearts;
    case Clubs;
    case Diamonds;
    case Spades;
}
