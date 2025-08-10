<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks;

use Henzeb\Enumhancer\Concerns\Bitmasks;


enum BitmaskPreferenceEnum: int
{
    use Bitmasks;

    private const BIT_VALUES = true;

    case LogActivity      = 1;
    case PushNotification = 2;
    case TwoFactorAuth    = 4;
    case DarkMode         = 8;
    case AutoUpdates      = 16;
    case DataExport       = 32;


    public static function allOptionsEnabled(): int
    {
        $all = self::cases();

        return self::mask(...$all)->value();
    }
}
