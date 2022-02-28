<?php


namespace Henzeb\Enumhancer\Tests\Fixtures;


use Henzeb\Enumhancer\Concerns\Makers;


enum IntBackedMakersEnum: int
{
    use Makers;

    case TEST = 0;
    case TEST_2 = 1;
}
