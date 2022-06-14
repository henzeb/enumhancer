<?php

namespace Henzeb\Enumhancer\Concerns;

use UnitEnum;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Exceptions\IllegalEnumTransitionException;

trait State
{
    /**
     * @throws IllegalEnumTransitionException
     */
    final public function transitionTo(self|string|int $state): self
    {
        $state = EnumMakers::cast(self::class, $state);

        if ($this->isTransitionAllowed($state)) {
            return $state;
        }

        IllegalEnumTransitionException::throw($this, $state);
    }

    /**
     * @param self|string|int $state
     * @return bool
     */
    final public function isTransitionAllowed(self|string|int $state): bool
    {
        /**
         * @var $this UnitEnum
         */
        $state = EnumMakers::cast(self::class, $state);

        return in_array($state, $this->allowedTransitions());
    }

    final public function allowedTransitions(): array
    {
        $transitions = array_change_key_case($this::class::transitions());
        $transitions = $transitions[$this->name] ?? $transitions[EnumValue::value($this)] ?? [];
        $transitions = is_array($transitions) ? $transitions : [$transitions];

        return array_map(fn($enum) => EnumMakers::cast(self::class, $enum), $transitions);
    }

    /**
     * @return self[]
     */
    public static function transitions(): array
    {
        $current = null;
        $transitions = [];
        foreach (self::class::cases() as $case) {
            if ($current) {
                $transitions[$current->name] = $case;
            }
            $current = $case;
        }
        unset($current);
        return $transitions;
    }
}
