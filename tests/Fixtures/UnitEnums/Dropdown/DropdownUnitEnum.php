<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Dropdown;

use Henzeb\Enumhancer\Concerns\Dropdown;

enum DropdownUnitEnum
{
    use Dropdown;

    case Orange;
    case Apple;
    case Banana;
}
