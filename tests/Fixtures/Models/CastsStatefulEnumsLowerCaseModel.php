<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedMakersEnum;
use Henzeb\Enumhancer\Laravel\Concerns\CastsStatefulEnumerations;

class CastsStatefulEnumsLowerCaseModel extends Model
{
    use CastsStatefulEnumerations;

    private $keepEnumCase = false;

    protected $casts = [
        'unitEnum' => SubsetUnitEnum::class,
        'intBackedEnum' => IntBackedEnum::class,
        'stringBackedEnum' => StringBackedMakersEnum::class
    ];
}
