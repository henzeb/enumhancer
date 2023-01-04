<?php

namespace Henzeb\Enumhancer\Laravel\Rules;

use Henzeb\Enumhancer\Helpers\Bitmasks\EnumBitmasks;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Illuminate\Contracts\Validation\Rule;

class EnumBitmask implements Rule
{
    private mixed $value = null;

    public function __construct(private readonly string $type, private readonly bool $singleBit = false)
    {
        EnumCheck::check($type);

        if (!EnumImplements::bitmasks($type)) {
            EnumBitmasks::triggerNotImplementingBitmasks($type);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        if ($this->singleBit) {
            return $value == 0
                || (EnumBitmasks::isBit($value)
                    && \array_key_exists($value, EnumBitmasks::getCaseBits($this->type))
                );
        }

        return EnumBitmasks::isValidBitmask($this->type, $value);
    }

    public function message(): string|array
    {
        $message = trans(
            'validation.enumhancer.bitmask',
            [
                'enum' => $this->type,
                'value' => $this->value,
            ]
        );

        return $message === 'validation.enumhancer.bitmask'
            ? ['The selected :attribute is invalid.']
            : $message;
    }
}
