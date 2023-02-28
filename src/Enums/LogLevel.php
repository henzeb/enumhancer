<?php

namespace Henzeb\Enumhancer\Enums;

use Henzeb\Enumhancer\Concerns\Configure;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Macros;

/**
 * @uses Enhancers<self>
 */
enum LogLevel
{
    use Enhancers, Configure, Macros;

    case Debug;
    case Info;
    case Notice;
    case Warning;
    case Error;
    case Critical;
    case Alert;
    case Emergency;
}
