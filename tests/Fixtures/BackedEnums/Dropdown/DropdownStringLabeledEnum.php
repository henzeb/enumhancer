<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Dropdown;

use Henzeb\Enumhancer\Concerns\Dropdown;
use Henzeb\Enumhancer\Concerns\Labels;

enum DropdownStringLabeledEnum: string
{
    use Dropdown, Labels;

    case Orange = 'My orange';
    case Apple = 'My apple';
    case Banana = 'My banana';

    public static function labels(): array
    {
        return [
            'Orange'=>'an orange',
            'Apple'=> 'an apple',
            'Banana'=>'a banana'
        ];
    }
}
