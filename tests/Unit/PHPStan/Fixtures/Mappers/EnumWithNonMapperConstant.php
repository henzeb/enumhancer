<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers;

enum EnumWithNonMapperConstant
{
    const NOT_MAPPER = 'true';
    case Suit;
}
