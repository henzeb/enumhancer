<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults;

use Henzeb\Enumhancer\Concerns\Defaults;

enum EnumWithCapitalizedDefault
{
    use Defaults;

    case Hearts;

    const DEFAULT = self::Hearts;
}
