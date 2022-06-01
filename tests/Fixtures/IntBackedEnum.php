<?php


namespace Henzeb\Enumhancer\Tests\Fixtures;


use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Comparison;


enum IntBackedEnum: int
{
    use Makers, Comparison;

    case TEST = 0;
    case TEST_2 = 1;
}
