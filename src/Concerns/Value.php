<?php

namespace Henzeb\Enumhancer\Concerns;

trait Value
{
    public function value(): string|int
    {
        return $this->value ?? strtolower($this->name);
    }
}
