<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes;

use Henzeb\Enumhancer\Concerns\Attributes;

#[Description('test'), AnotherAttribute]
enum ClassAttributesEnum: string
{
    use Attributes {
        getEnumAttribute as public;
        getEnumAttributes as public;
    }

    case Attribute = 'attribute';
}
