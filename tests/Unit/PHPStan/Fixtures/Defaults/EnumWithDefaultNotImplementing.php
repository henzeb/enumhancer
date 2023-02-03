<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults;

enum EnumWithDefaultNotImplementing
{
    case Hearts;

    const Default = self::Hearts;
}
