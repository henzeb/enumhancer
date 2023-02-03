<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Macros;

use Henzeb\Enumhancer\Concerns\Macros;

/**
 * @method static void aStaticMacroCall();
 */
enum MacrosLogicExceptionEnum
{
    use Macros;

    case Test;

    public function test(): void
    {
        self::aStaticMacroCall();
    }
}
