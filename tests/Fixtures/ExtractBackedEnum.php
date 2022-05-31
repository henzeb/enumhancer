<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Extractor;

enum ExtractBackedEnum: string
{
    use Extractor;

    case ENUM = 'enum';
    case AN_ENUM = 'an enum';
    case ANOTHER_ENUM = 'another enum';
    case ENUM_3 = 'third_enum';
}
