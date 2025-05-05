<?php

namespace Henzeb\Enumhancer\Laravel\Rules;

use ErrorException;
use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Illuminate\Contracts\Validation\Rule;
use UnitEnum;
use const E_USER_ERROR;

class EnumTransition implements Rule
{

    private mixed $transitionTo = null;

    final public function __construct(
        private readonly UnitEnum $currentState,
        private readonly ?TransitionHook $hook = null
    ) {
        if (!EnumImplements::state($this->currentState::class)) {
            throw new ErrorException(
                sprintf('%s does not implement `State`', $this->currentState::class),
                E_USER_ERROR
            );
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value)
    {
        $this->transitionTo = $value;

        /**
         * @var State $currentState
         */
        $currentState = $this->currentState;

        return $currentState->isTransitionAllowed($value, $this->hook);
    }

    /**
     * Get the validation error message.
     *
     * @return string[]
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
