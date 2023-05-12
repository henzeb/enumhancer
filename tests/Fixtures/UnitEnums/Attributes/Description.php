<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Description
{
    public function __construct(public string $value)
    {
    }
}
