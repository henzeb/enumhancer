<?php

namespace Henzeb\Enumhancer\Tests\Helpers;


use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Helpers\EnumProperties;

trait ClearsEnumProperties
{
    public function tearDown(): void
    {
        if(!$this instanceof TestCase) {
            throw new \RuntimeException('not allowed!');
        }

        $class = new class extends EnumProperties {
            public function clearOnce() {
                self::clearGlobal();
                self::$properties = [];
                self::$once = [];
            }
        };

        $class->clearOnce();
    }
}
