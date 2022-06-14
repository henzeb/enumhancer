<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State;

use Henzeb\Enumhancer\Concerns\State;

enum StateElevatorEnum
{
    use State;

    case Open;
    case Close;
    case Move;
    case Stop;
}
