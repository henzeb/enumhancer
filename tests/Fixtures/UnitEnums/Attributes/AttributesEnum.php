<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes;

use Henzeb\Enumhancer\Concerns\Attributes;

enum AttributesEnum: string
{
    use Attributes {
        getAttribute as public;
        getAttributes as public;
        getEnumAttribute as public;
        getEnumAttributes as public;
    }

    #[Description('has description')]
    case WithAttribute = 'with';

    case WithoutAttribute = 'without';

    #[Description('has description'), Description('and another one')]
    case WithMultipleAttributes = 'multiple';

    #[Description('has description'), AnotherAttribute]
    case WithMixedAttributes = 'mixed';
}
