<?php

namespace Henzeb\Enumhancer\Concerns;

use UnitEnum;
use ValueError;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumCompare;

trait Defaults
{
    static public function default(): ?UnitEnum
    {
        try {
            return EnumMakers::make(self::class, 'default', true);
        } catch (ValueError) {
            return null;
        }
    }

    public function isDefault(): bool
    {
        $default = self::default();

        if ($default) {
            return EnumCompare::equals($this, $default);
        }

        return false;
    }

    public function isNotDefault(): bool
    {
        return !$this->isDefault();
    }
}
