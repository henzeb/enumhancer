<?php


namespace Henzeb\Enumhancer\Tests\Fixtures;


use Henzeb\Enumhancer\Concerns\Makers;


enum IntBackedMakersEnum: int
{
    use Makers;

    case TEST = 0;
}