<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumProperties;
use UnitEnum;
use Henzeb\Enumhancer\Helpers\EnumState;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\SyntaxException;
use Henzeb\Enumhancer\Exceptions\IllegalEnumTransitionException;
use Henzeb\Enumhancer\Exceptions\IllegalNextEnumTransitionException;

trait State
{
    /**
     * @throws IllegalEnumTransitionException|SyntaxException
     */
    public function transitionTo(self|string|int $state, TransitionHook $hook = null): self
    {
        $state = EnumMakers::cast(self::class, $state);

        if ($this->isTransitionAllowed($state, $hook)) {
            $hook?->execute($this, $state);
            self::transitionHook()?->execute($this, $state);

            return $state;
        }

        throw new IllegalEnumTransitionException($this, $state);
    }

    /**
     * @param self|string|int $state
     * @param TransitionHook|null $hook
     * @return bool
     * @throws SyntaxException
     */
    public function isTransitionAllowed(self|string|int $state, TransitionHook $hook = null): bool
    {
        /**
         * @var $this UnitEnum
         */
        $state = EnumMakers::cast(self::class, $state);

        return in_array($state, $this->allowedTransitions($hook));
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
