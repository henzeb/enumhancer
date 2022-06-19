<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State;

use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Helpers\EnumValue;

enum StateElevatorComplexEnum
{
    use State;

    case Open;
    case Close;
    case Move;
    case Stop;

    private static function customTransitions(): array
    {
        return [
            'Close' => [
                self::Open,
                'Move',
            ],
            self::Move->name => 'Stop',
            Enumvalue::value(self::Stop) => 'Open'
        ];
    }
}
