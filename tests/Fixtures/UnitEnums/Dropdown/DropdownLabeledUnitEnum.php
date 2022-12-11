<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Dropdown;

use Henzeb\Enumhancer\Concerns\Dropdown;
use Henzeb\Enumhancer\Concerns\Labels;

enum DropdownLabeledUnitEnum
{
    use Dropdown, Labels;
    case Orange;
    case Apple;
    case Banana;

    public static function labels(): array
    {
        return [
            'Orange'=>'an orange',
            'Apple'=> 'an apple',
            'Banana'=>'a banana'
        ];
    }
}
