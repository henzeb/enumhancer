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
        return $this->labels()[$this->name] ?? $this->name;
    }
}
