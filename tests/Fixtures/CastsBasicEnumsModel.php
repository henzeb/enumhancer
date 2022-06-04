<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Laravel\Concerns\CastsBasicEnumerations;

class CastsBasicEnumsModel extends Model
{
    use CastsBasicEnumerations;

    protected $casts = [
        'unitEnum' => SubsetUnitEnum::class,
        'intBackedEnum' => IntBackedEnum::class,
        'stringBackedEnum' => StringBackedMakersEnum::class
    ];
}
