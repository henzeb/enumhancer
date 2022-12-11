<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumProperties;

trait Labels
{
    public static function labels(): array
    {
        return
            EnumProperties::get(
                self::class,
                EnumProperties::reservedWord('labels')
            ) ?? [];
    }

    public function label(): ?string
    {
        return self::labels()[$this->name]
            ?? (method_exists($this, 'value') ? $this->value() : null)
            ?? $this->value
            ?? $this->name;
    }
}
