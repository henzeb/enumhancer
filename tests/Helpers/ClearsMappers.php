<?php

namespace Henzeb\Enumhancer\Tests\Helpers;


use Closure;
use Henzeb\Enumhancer\Contracts\Mapper;
use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Helpers\EnumProperties;

trait ClearsMappers
{
    private function clearMappers(): void {
        Closure::bind(
            function () {
                Mapper::$instances = [];
                Mapper::$flippedInstances = [];
            },
            null,
            Mapper::class
        )();
    }
}
