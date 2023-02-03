<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers;

use Henzeb\Enumhancer\Concerns\Enhancers;

enum MappersEnum
{
    use Enhancers;

    case Hearts;
    case Diamonds;

    private const MapInvalid = true;
}
