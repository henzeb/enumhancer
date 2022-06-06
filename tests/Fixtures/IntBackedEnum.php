<?php


namespace Henzeb\Enumhancer\Tests\Fixtures;


use Henzeb\Enumhancer\Concerns\Makers;
use Henzeb\Enumhancer\Concerns\Comparison;

/**
 * @method isTest() bool
 * @method is1() bool
 * @method isTest_2()
 * @method is0()
 * @method isNotTest()
 * @method isNot0()
 */
enum IntBackedEnum: int
{
    use Makers, Comparison;

    case TEST = 0;
    case TEST_2 = 1;
}
