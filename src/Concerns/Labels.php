<?php

namespace Henzeb\Enumhancer\Concerns;

trait Labels
{
    public function labels(): array
    {
        return [];
    }

    final public function label(): ?string
    {
        return $this->labels()[$this->name]
            ?? (method_exists($this, 'value') ? $this->value() : null)
            ?? $this->value
            ?? $this->name;
    }
}
