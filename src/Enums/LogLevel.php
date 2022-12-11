<?php

namespace Henzeb\Enumhancer\Enums;

use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Configure;

enum LogLevel
{
    use Enhancers, Configure;

    case Debug;
    case Info;
    case Notice;
    case Warning;
    case Error;
    case Critical;
    case Alert;
    case Emergency;
}
