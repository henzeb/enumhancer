<?php

namespace Henzeb\Enumhancer\Enums;

use Henzeb\Enumhancer\Concerns\Enhancers;

enum LogLevel
{
    use Enhancers;

    case Emergency;
    case Alert;
    case Critical;
    case Error;
    case Warning;
    case Notice;
    case Info;
    case Debug;

    public static function default(): ?self
    {
        return self::Notice;
    }
}
