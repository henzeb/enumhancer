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
        try {
            EnumImplements::doesNotExist(SimpleEnum::class);
            $this->fail('Expected exception was not thrown');
        } catch (Throwable $e) {
            $this->assertEquals(
                'Call to undefined method Henzeb\Enumhancer\Helpers\EnumImplements::doesNotExist()',
                $e->getMessage()
            );
        }
    }
}
