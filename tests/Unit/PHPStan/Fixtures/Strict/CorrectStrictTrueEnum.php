<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Strict;

use Henzeb\Enumhancer\Concerns\Enhancers;

enum CorrectStrictTrueEnum
{
    use Enhancers;

    const STRICT = true;
}
