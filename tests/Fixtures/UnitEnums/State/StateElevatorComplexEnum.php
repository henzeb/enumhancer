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

    public static function transitions(): array
    {
        return [
            'Open' => self::Close,
            'Close' => [
                self::Open,
                'Move',
            ],
            self::Move->name => 'Stop',
            Enumvalue::value(self::Stop) => 'Open'
        ];
    }
}
