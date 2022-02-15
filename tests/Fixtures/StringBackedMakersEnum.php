<?php


namespace Henzeb\Enumhancer\Tests\Fixtures;


use Henzeb\Enumhancer\Concerns\Makers;


enum StringBackedMakersEnum: string
{
    use Makers;

    case TEST = 'TEST';

    case TEST1 = 'different';

    case TEST_STRING_TO_UPPER = 'STRINGTOUPPER';
}
