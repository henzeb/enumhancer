<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use Exception;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use PHPUnit\Framework\TestCase;
use Throwable;

class EnumImplementsTest extends TestCase
{
    public function testImplementsShouldFail()
    {
        set_error_handler(fn($code, $message) => throw new Exception($message));

        try {
            EnumImplements::doesNotExist(SimpleEnum::class);

        } catch (Throwable $e) {
            
            $this->assertEquals(
                'Call to undefined method Henzeb\Enumhancer\Helpers\EnumImplements::doesNotExist()',
                $e->getMessage()
            );
        } finally {
            restore_error_handler();
        }
    }
}
