<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Strict;

use Henzeb\Enumhancer\Concerns\Enhancers;

enum CorrectStrictFalseEnum
{
    use Enhancers;

    const STRICT = false;
}
