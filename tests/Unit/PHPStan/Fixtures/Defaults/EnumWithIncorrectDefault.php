<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults;

use Henzeb\Enumhancer\Concerns\Defaults;

enum EnumWithIncorrectDefault
{
    use Defaults;

    case Hearts;

    const DEFAULT = 'string';
}
