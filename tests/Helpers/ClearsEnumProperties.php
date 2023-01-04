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

        \Closure::bind(function(){
            EnumProperties::clearGlobal();
            EnumProperties::$properties = [];
            EnumProperties::$once = [];
        }, null,
            EnumProperties::class
        )();
    }
}
