<?php

namespace Henzeb\Enumhancer\Contracts;

use UnitEnum;
use BackedEnum;

interface MultiEnum
{
    public function equals(BackedEnum|UnitEnum|string ...$enum): bool;
}
