<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State;

use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Concerns\Properties;
use Henzeb\Enumhancer\Contracts\TransitionHook;

/**
 * @method tryToClose()
 * @method tryToMove()
 * @method toClose()
 * @method toMove()
 * @method doesNotExist()
 */
enum StateElevatorEnum
{
    use State, Properties;

    case Open;
    case Close;
    case Move;
    case Stop;

    public static function setTransitionHook(TransitionHook $hook): void
    {
        self::property('hook', $hook);
    }

    protected static function transitionHook(): ?TransitionHook
    {
        return self::property('hook');
    }
}
