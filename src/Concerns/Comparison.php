<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumCompare;
use UnitEnum;

trait Comparison
{
    use MagicCalls;

    public function equals(UnitEnum|string|int|null ...$equals): bool
    {
        return EnumCompare::equals($this, ...$equals);
    }

    public function is(UnitEnum|string|int|null $equals): bool
    {
        return $this->equals($equals);
    }

    public function isNot(UnitEnum|string|int|null $equals): bool
    {
        return !$this->is($equals);
    }

    public function isIn(UnitEnum|string|int|null ...$equals): bool
    {
        return $this->equals(...$equals);
    }

    public function isNotIn(UnitEnum|string|int|null ...$equals): bool
    {
        return !$this->equals(...$equals);
    }
}
