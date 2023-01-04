<?php

namespace Henzeb\Enumhancer\Helpers\Bitmasks\Concerns;

use Henzeb\Enumhancer\Helpers\Bitmasks\EnumBitmasks;
use Henzeb\Enumhancer\Helpers\EnumCheck;

trait BitmaskValidators
{
    public function for(string $enumclass): bool
    {
        EnumCheck::check($enumclass);

        return $this->enumFQCN === $enumclass;
    }

    public function forOrFail(string $enumClass): bool
    {
        if ($this->for($enumClass)) {
            return true;
        }

        EnumBitmasks::throwMismatch(
            $this->forEnum(),
            $enumClass
        );
    }

    public function forEnum(): string
    {
        return $this->enumFQCN;
    }
}
