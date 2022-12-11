<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown;

use Henzeb\Enumhancer\Concerns\Dropdown;
use Henzeb\Enumhancer\Concerns\Labels;

enum DropdownIntLabeledEnum: int
{
    use Dropdown, Labels;

    case Orange = 2;
    case Apple = 3;
    case Banana = 5;

    public static function labels(): array
    {
        return [
            'Orange'=>'an orange',
            'Apple'=> 'an apple',
            'Banana'=>'a banana'
        ];
    }
}
