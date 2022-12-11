<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;
use Henzeb\Enumhancer\Helpers\EnumProperties;

trait ConfigureState
{
    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setTransitionHook(?TransitionHook $hook): void
    {
        EnumProperties::store(
            self::class,
            EnumProperties::reservedWord('hooks'),
            $hook,
            true
        );
    }

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setTransitionHookOnce(TransitionHook $hook): void
    {
        EnumProperties::storeOnce(
            self::class,
            EnumProperties::reservedWord('hooks'),
            $hook,
            true
        );
    }

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setTransitions(array $transitions): void
    {
        EnumProperties::store(
            self::class,
            EnumProperties::reservedWord('state'),
            $transitions,
            true
        );
    }

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setTransitionsOnce(array $transitions): void
    {
        EnumProperties::storeOnce(
            self::class,
            EnumProperties::reservedWord('state'),
            $transitions,
            true
        );
    }
}
