<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Reporters;


use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Log;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;

class LaravelLogReporterTest extends TestCase
{
    public function testShouldLog()
    {
        $spy = Log::spy();

        (new LaravelLogReporter())->report(EnhancedBackedEnum::class, 'KEY', null);

        $spy->shouldHaveReceived('warning', [
            "EnhancedBackedEnum does not have 'KEY'",
            [
                'class' => 'EnhancedBackedEnum',
                'key' => 'KEY',
            ]
        ]);
    }

    public function testShouldLogWithContext()
    {
        $spy = Log::spy();

        (new LaravelLogReporter())->report(EnhancedBackedEnum::class, 'KEY', EnhancedBackedEnum::ANOTHER_ENUM);

        $spy->shouldHaveReceived('warning', [
            "EnhancedBackedEnum does not have 'KEY'",
            [
                'class' => 'EnhancedBackedEnum',
                'key' => 'KEY',
                'context' => EnhancedBackedEnum::ANOTHER_ENUM->value
            ]
        ]);
    }


}
