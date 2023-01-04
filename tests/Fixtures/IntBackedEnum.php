<?php


namespace Henzeb\Enumhancer\Tests\Fixtures;


use Henzeb\Enumhancer\Concerns\Bitmasks;
use Henzeb\Enumhancer\Concerns\Configure;
use Henzeb\Enumhancer\Concerns\ConfigureDefaults;
use Henzeb\Enumhancer\Concerns\Defaults;
use Henzeb\Enumhancer\Concerns\Getters;
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
    use Makers, Getters, Comparison, Bitmasks, ConfigureDefaults, Defaults;

    case TEST = 0;
    case TEST_2 = 1;
    case TEST_3 = 2;

}
