<?php

namespace Henzeb\Enumhancer\Laravel\Mixins;

use Closure;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Laravel\Rules\EnumBitmask;
use Henzeb\Enumhancer\Laravel\Rules\EnumTransition;
use Henzeb\Enumhancer\Laravel\Rules\IsEnum as UnitEnumRule;
use UnitEnum;

class RulesMixin
{
    public function isEnum(): Closure
    {
        return function (string $type, Mapper|string|array|null ...$mappers): UnitEnumRule {
            return new UnitEnumRule($type, ...$mappers);
        };
    }

    public function enumBitmask(): Closure
    {
        return function (string $type, bool $singleBit = false): EnumBitmask {
            return new EnumBitmask($type, $singleBit);
        };
    }

    public function enumTransition(): Closure
    {
        return function (UnitEnum $currentState, TransitionHook $hook = null): EnumTransition {
            return new EnumTransition($currentState, $hook);
        };
    }
}
