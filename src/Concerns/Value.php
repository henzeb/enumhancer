<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumValue;

trait Value
{
    final public function value(): string|int
    {
        return EnumValue::value($this);
    }
}
