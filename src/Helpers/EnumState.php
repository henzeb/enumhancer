<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use UnitEnum;

/**
 * @internal
 */
final class EnumState
{
    public static function transitions(string $class, array $userTransitions): array
    {
        EnumCheck::check($class);

        $current = null;
        $transitions = [];

        /**
         * @var $class UnitEnum|string
         */
        foreach ($class::cases() as $case) {
            if ($current) {
                $transitions[$current->name] = $case;
            }

            $current = $case;
        }
        unset($current);

        return array_merge(
            $transitions,
            self::castTransitions($class, $userTransitions)
        );
    }

    public static function allowedTransitions(UnitEnum $currentTransition, ?TransitionHook ...$hooks): array
    {
        return self::filterAllowedTransitions($currentTransition, self::getTransitions($currentTransition), $hooks);
    }

    private static function getTransitions(UnitEnum $currentTransition): array
    {
        $transitions = array_change_key_case(
            EnumState::transitions($currentTransition::class, $currentTransition::class::transitions())
        );

        $transitions = $transitions[$currentTransition->name]
            ?? $transitions[EnumValue::value($currentTransition)]
            ?? [];

        return array_filter(is_array($transitions) ? $transitions : [$transitions]);
    }

    private static function castTransitions(string $class, array $transitions): array
    {

        foreach ($transitions as $key => $value) {
            unset($transitions[$key]);

            $key = EnumGetters::tryCast($class, $key)?->name ?? $key;

            if (is_array($value)) {
                $transitions[$key] = self::castTransitions($class, $value);
                continue;
            }

            $transitions[$key] = $value ? EnumGetters::cast($class, $value) : null;
        }

        return $transitions;
    }

    private static function filterAllowedTransitions(
        UnitEnum $currentTransition,
        array $transitions,
        array $hooks
    ): array {
        return array_filter(
            $transitions,
            function (UnitEnum $transitionTo) use ($hooks, $currentTransition) {
                foreach ($hooks as $hook) {
                    if (!$hook->isAllowed($currentTransition, $transitionTo)) {
                        return false;
                    }
                }
                return true;
            }
        );
    }

    public static function isValidCall(string $class, string $name): bool
    {
        EnumCheck::check($class);

        return str_starts_with($name, 'to') || str_starts_with($name, 'tryTo');
    }

    public static function call(UnitEnum|State $currentState, string $name, array $arguments): mixed
    {
        $toState = EnumGetters::tryGet(
            $currentState::class,
            substr($name, str_starts_with($name, 'tryTo') ? 5 : 2),
            true
        );

        if (str_starts_with($name, 'tryTo')) {
            return $currentState->tryTo($toState, ...$arguments);
        }

        return $currentState->to($toState, ...$arguments);
    }
}
