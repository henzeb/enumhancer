<?php

namespace Henzeb\Enumhancer\Concerns;

use ValueError;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumCompare;

trait Defaults
{
    public static function default(): ?self
    {
        try {
            return EnumMakers::make(self::class, 'default', true);
        } catch (ValueError) {
            return null;
        }
    }

    public function isDefault(): bool
    {
        if ($default = self::default()) {
            return EnumCompare::equals($this, $default);
        }
        return false;
    }

    public function isNotDefault(): bool
    {
        return !$this->isDefault();
    }
}
