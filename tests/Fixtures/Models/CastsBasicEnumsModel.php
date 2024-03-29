<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Laravel\Concerns\CastsBasicEnumerations;

class CastsBasicEnumsModel extends Model
{
    use CastsBasicEnumerations;

    protected $casts = [
        'unitEnum' => SubsetUnitEnum::class,
        'intBackedEnum' => IntBackedEnum::class,
        'stringBackedEnum' => StringBackedGetEnum::class
    ];
}
