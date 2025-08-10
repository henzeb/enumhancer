<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\Models;

use Henzeb\Enumhancer\Laravel\Casts\AsBitmask;
use Henzeb\Enumhancer\Laravel\Traits\InteractsWithBitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmaskPreferenceEnum;
use Illuminate\Database\Eloquent\Model;


class CastsBitmaskEnumsModel extends Model
{
    use InteractsWithBitmask;

    protected $table   = 'casts_bitmask_enums';
    protected $guarded = [];


    # casts
    protected $casts = [
        'preferences' => AsBitmask::class . ':' . BitmaskPreferenceEnum::class,
    ];
}
