<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\IllegalEnumTransitionException;
use Henzeb\Enumhancer\Exceptions\IllegalNextEnumTransitionException;
use Henzeb\Enumhancer\Exceptions\SyntaxException;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Helpers\EnumState;
use UnitEnum;

trait State
{
    use MagicCalls;

    /**
     * @throws IllegalEnumTransitionException|SyntaxException
     */
    public function transitionTo(self|string|int $state, TransitionHook $hook = null): self
    {
        $state = EnumGetters::cast(self::class, $state);

        if ($this->isTransitionAllowed($state, $hook)) {
            $hook?->execute($this, $state);
            self::transitionHook()?->execute($this, $state);

            return $state;
        }

        throw new IllegalEnumTransitionException($this, $state);
    }

    public function to(self|string|int $state, TransitionHook $hook = null): self
    {
        return $this->transitionTo($state, $hook);
    }

    public function tryTo(self|string|int $state, TransitionHook $hook = null): self
    {
        if ($this->isTransitionAllowed($state, $hook)) {
            return $this->transitionTo($state, $hook);
        }
        return $this;
    }

    /**
     * @param self|string|int $state
     * @param TransitionHook|null $hook
     * @return bool
     */
    public function isTransitionAllowed(self|string|int $state, TransitionHook $hook = null): bool
    {
        /**
         * @var $this UnitEnum
         */
        $state = EnumGetters::tryCast(self::class, $state);

        return $state && in_array($state, $this->allowedTransitions($hook));
    }

    /**
     * @param TransitionHook|null $hook
     * @return array
     */
    public function allowedTransitions(TransitionHook $hook = null): array
    {
        return EnumState::allowedTransitions(
            $this,
            ...array_filter([$hook, self::transitionHook()])
        );
    }

    /**
     * @return self[]
     */
    public static function transitions(): array
    {
        return EnumState::transitions(self::class, self::customTransitions());
    }

    protected static function customTransitions(): array
    {
        return EnumProperties::get(self::class, EnumProperties::reservedWord('state')) ?? [];
    }

    protected static function transitionHook(): ?TransitionHook
    {
        return EnumProperties::get(
            self::class,
            EnumProperties::reservedWord('hooks')
        );
    }
}
