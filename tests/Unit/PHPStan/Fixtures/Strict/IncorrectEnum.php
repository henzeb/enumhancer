<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Strict;

use Henzeb\Enumhancer\Concerns\Enhancers;

enum IncorrectEnum
{
    use Enhancers;

    const STRICT = 'should fail';
}
