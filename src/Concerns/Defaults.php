<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumDefaults;

trait Defaults
{
    public static function default(): ?self
    {
        return EnumDefaults::default(self::class);
    }

    public function isDefault(): bool
    {
        return EnumDefaults::isDefault($this);
    }

    public function isNotDefault(): bool
    {
        return !$this->isDefault();
    }
}
