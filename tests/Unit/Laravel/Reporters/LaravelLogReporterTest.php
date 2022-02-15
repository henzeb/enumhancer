<?php

namespace Unit\Laravel\Reporters;


use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedEnum;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;

class LaravelLogReporterTest extends TestCase
{
    public function testShouldLog()
    {
        $spy = Log::spy();

        (new LaravelLogReporter())->report(EnhancedEnum::class, 'KEY', null);

        $spy->shouldHaveReceived('warning', [
            "EnhancedEnum does not have 'KEY'",
            [
                'class' => 'EnhancedEnum',
                'key' => 'KEY',
            ]
        ]);
    }

    public function testShouldLogWithContext()
    {
        $spy = Log::spy();

        (new LaravelLogReporter())->report(EnhancedEnum::class, 'KEY', EnhancedEnum::ANOTHER_ENUM);

        $spy->shouldHaveReceived('warning', [
            "EnhancedEnum does not have 'KEY'",
            [
                'class' => 'EnhancedEnum',
                'key' => 'KEY',
                'context' => EnhancedEnum::ANOTHER_ENUM->value
            ]
        ]);
    }



}
