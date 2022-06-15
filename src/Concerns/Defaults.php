<?php

namespace Henzeb\Enumhancer\Concerns;

use ValueError;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumCompare;

trait Defaults
{
    final static public function default(): ?self
    {
        try {
            return EnumMakers::make(self::class, 'default', true);
        } catch (ValueError) {
            return null;
        }
    }

    final public function isDefault(): bool
    {
        $default = self::default();

        if ($default) {
            return EnumCompare::equals($this, $default);
        }

        return false;
    }

    final public function isNotDefault(): bool
    {
        return !$this->isDefault();
    }
}
