<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State;

use Henzeb\Enumhancer\Concerns\State;

enum StateElevatorDisableTransitionEnum
{
    use State;

    case Open;
    case Close;
    case Move;
    case Stop;

    protected static function customTransitions(): array
    {
        return [
            'open'=> null
        ];
    }
}
