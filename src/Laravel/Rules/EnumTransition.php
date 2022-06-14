<?php

namespace Henzeb\Enumhancer\Laravel\Rules;

use UnitEnum;
use Henzeb\Enumhancer\Concerns\State;
use Illuminate\Contracts\Validation\Rule;

class EnumTransition implements Rule
{
    /**
     * @var UnitEnum|State
     */
    private UnitEnum $currentState;
    private ?string $to = null;

    public function __construct(UnitEnum $currentState)
    {
        $this->currentState = $currentState;
    }

    public function passes($attribute, $value)
    {
        $this->to = $value;
        return $this->currentState->isTransitionAllowed($value);
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
            'to' => $this->to ?? 'unknown',
        ]);

        return $message === 'validation.enumhancer.transition'
            ? ['The transition for :attribute is invalid.']
            : $message;
    }
}
