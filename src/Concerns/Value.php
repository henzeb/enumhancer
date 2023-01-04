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
        return EnumValue::key($this);
    }
}
