<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults;

enum EnumWithIncorrectDefaultNotImplementing
{
    case Hearts;

    const Default = false;
}
