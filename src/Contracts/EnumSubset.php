<?php

namespace Henzeb\Enumhancer\Contracts;

use Closure;
use UnitEnum;
use BackedEnum;

interface EnumSubset
{
    public function do(Closure $callable): void;

    public function equals(BackedEnum|UnitEnum|string ...$enum): bool;

    public function names(): array;

    public function values(): array;

    public function cases(): array;
}
