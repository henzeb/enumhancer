<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown;

use Henzeb\Enumhancer\Concerns\Dropdown;

enum DropdownIntEnum: int
{
    use Dropdown;

    case Orange = 2;
    case Apple = 3;
    case Banana = 5;
}
