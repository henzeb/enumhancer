<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumValue;

trait Value
{
    public function value(): string|int
    {
        return EnumValue::value($this);
    }

    public function key(): int
    {
        if (property_exists($this, 'value') && is_numeric($this->value)) {
            return (int)$this->value;
        }

        return array_search($this, $this::cases());
    }
}
