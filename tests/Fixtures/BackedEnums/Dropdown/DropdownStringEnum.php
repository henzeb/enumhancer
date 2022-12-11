<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown;

use Henzeb\Enumhancer\Concerns\Dropdown;

enum DropdownStringEnum: string
{
    use Dropdown;

    case Orange = 'My orange';
    case Apple = 'My apple';
    case Banana = 'My banana';
}
