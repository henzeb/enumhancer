<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumLabels;

trait Labels
{
    public static function labels(): array
    {
        return EnumLabels::getConfiguredLabels(self::class);
    }

    public function label(): ?string
    {
        return EnumLabels::getLabel($this);
    }
}
