<?php

namespace Henzeb\Enumhancer\Concerns;

use UnitEnum;
use ValueError;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumCompare;
use Henzeb\Enumhancer\Helpers\EnumProperties;

trait Defaults
{
    static public function default(): ?UnitEnum
    {
        try {
            return
                EnumProperties::get(self::class, EnumProperties::reservedWord('defaults'))
                ?? EnumMakers::make(self::class, 'default', true);
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
