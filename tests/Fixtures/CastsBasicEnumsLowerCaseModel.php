<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Laravel\Concerns\CastsBasicEnumerations;

class CastsBasicEnumsLowerCaseModel extends Model
{
    use CastsBasicEnumerations;

    protected $keepEnumCase = false;

    protected $casts = [
        'unitEnum' => SubsetUnitEnum::class,
        'intBackedEnum' => IntBackedEnum::class,
        'stringBackedEnum' => StringBackedMakersEnum::class
    ];
}
