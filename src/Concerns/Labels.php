<?php

namespace Henzeb\Enumhancer\Concerns;

trait Labels
{
    public static function labels(): array
    {
        return [];
    }

    public function label(): ?string
    {
        return self::labels()[$this->name]
            ?? (method_exists($this, 'value') ? $this->value() : null)
            ?? $this->value
            ?? $this->name;
    }
}
