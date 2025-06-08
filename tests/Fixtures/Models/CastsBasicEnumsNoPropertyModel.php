<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Laravel\Concerns\CastsBasicEnumerations;

class CastsBasicEnumsNoPropertyModel extends Model
{
    use CastsBasicEnumerations;

    // No keepEnumCase property - this will test the property_exists fallback

    protected $casts = [
        'unitEnum' => SubsetUnitEnum::class,
    ];
}