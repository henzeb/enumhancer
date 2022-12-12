<?php


namespace Henzeb\Enumhancer\Tests\Fixtures;


use Henzeb\Enumhancer\Concerns\Getters;
use Henzeb\Enumhancer\Concerns\State;
use Henzeb\Enumhancer\Concerns\Makers;


enum StringBackedGetEnum: string
{
    use Makers, Getters, State;

    case TEST = 'TEST';

    case TEST1 = 'different';

    case TEST_STRING_TO_UPPER = 'STRINGTOUPPER';

    case Translated = 'Translated';
}
