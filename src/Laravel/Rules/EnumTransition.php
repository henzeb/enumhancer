<?php

namespace Henzeb\Enumhancer\Laravel\Rules;

use UnitEnum;
use Henzeb\Enumhancer\Concerns\State;
use Illuminate\Contracts\Validation\Rule;
use Henzeb\Enumhancer\Contracts\TransitionHook;

class EnumTransition implements Rule
{
    /**
     * @var UnitEnum|State
     */
    private UnitEnum $currentState;
    private ?string $transitionTo = null;
    private ?TransitionHook $hook;

    public function __construct(UnitEnum $currentState, TransitionHook $hook = null)
    {
        $this->currentState = $currentState;
        $this->hook = $hook;
    }

    /**
     * @param $attribute
     * @param $value
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value)
    {
        $this->transitionTo = $value;
        return $this->currentState->isTransitionAllowed($value, $this->hook);
    }

    /**
     * Get the validation error message.
     *
     * @return array
     */
    public function message()
    {
        $message = trans('validation.enumhancer.transition', [
            'from' => $this->currentState->name,
            'to' => $this->transitionTo ?? 'unknown',
        ]);

        return $message === 'validation.enumhancer.transition'
            ? ['The transition for :attribute is invalid.']
            : $message;
    }
}
