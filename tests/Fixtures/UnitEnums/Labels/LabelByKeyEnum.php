<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Labels;

use Henzeb\Enumhancer\Concerns\Labels;

enum LabelByKeyEnum
{
    use Labels;

    case LabelByKey;
    case LabelByKey2;
    case LabelByKey3;

    public static function labels(): array
    {
        return [
            'label 1',
            'label 2',
            'label 3',
        ];
    }
}
