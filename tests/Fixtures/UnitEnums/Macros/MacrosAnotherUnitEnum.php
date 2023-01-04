<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros;

use Henzeb\Enumhancer\Concerns\Macros;

enum MacrosAnotherUnitEnum
{
    use Macros;

    case Create;
    case Read;
    case Update;
    case Delete;
}
