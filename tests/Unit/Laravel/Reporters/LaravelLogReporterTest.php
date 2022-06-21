<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Reporters;


use Mockery;
use Psr\Log\LoggerInterface;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Log;
use Henzeb\Enumhancer\Enums\LogLevel;
use Illuminate\Support\Facades\Config;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;

class LaravelLogReporterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('logging.default', 'stack');
    }

    public function testShouldLog()
    {
        $spy = Mockery::spy(LoggerInterface::class);

        Log::shouldReceive('stack')
            ->once()
            ->with(['stack'])
            ->andReturn($spy);

        (new LaravelLogReporter())->report(EnhancedBackedEnum::class, 'KEY', null);

        $spy->shouldHaveReceived(
            'log',
            [
                'notice',
                "EnhancedBackedEnum does not have 'KEY'",
                [
                    'class' => EnhancedBackedEnum::class,
                    'key' => 'KEY',
                ]
            ]
        );
    }

    public function testShouldLogWithContext()
    {
        $spy = Mockery::spy(LoggerInterface::class);

        Log::shouldReceive('stack')
            ->once()
            ->with(['stack'])
            ->andReturn($spy);

        (new LaravelLogReporter())->report(EnhancedBackedEnum::class, 'KEY', EnhancedBackedEnum::ANOTHER_ENUM);


        $spy->shouldHaveReceived('log',
            [
                'notice',
                "EnhancedBackedEnum does not have 'KEY'",
                [
                    'class' => EnhancedBackedEnum::class,
                    'key' => 'KEY',
                    'context' => EnhancedBackedEnum::ANOTHER_ENUM->value
                ]
            ]
        );
    }

    public function testShouldUseDifferentLevel()
    {
        $spy = Mockery::spy(LoggerInterface::class);

        Log::shouldReceive('stack')
            ->once()
            ->with(['stack'])
            ->andReturn($spy);

        (new LaravelLogReporter(LogLevel::Alert))->report(EnhancedBackedEnum::class, 'KEY', null);

        $spy->shouldHaveReceived(
            'log',
            [
                'alert',
                "EnhancedBackedEnum does not have 'KEY'",
                [
                    'class' => EnhancedBackedEnum::class,
                    'key' => 'KEY'
                ]
            ]
        );
    }

    public function testShouldUseDifferentChannel()
    {
        $spy = Mockery::spy(LoggerInterface::class);

        Log::shouldReceive('stack')
            ->once()
            ->with(['slack'])
            ->andReturn($spy);

        (new LaravelLogReporter(LogLevel::default(), 'slack'))->report(EnhancedBackedEnum::class, 'KEY', null);

        $spy->shouldHaveReceived(
            'log',
            [
                'notice',
                "EnhancedBackedEnum does not have 'KEY'",
                [
                    'class' => EnhancedBackedEnum::class,
                    'key' => 'KEY'
                ]
            ]
        );
    }

    public function testShouldUseDifferentChannels()
    {
        $spy = Mockery::spy(LoggerInterface::class);

        Log::shouldReceive('stack')
            ->once()
            ->with(['slack', 'bugsnag'])
            ->andReturn($spy);

        (new LaravelLogReporter(LogLevel::default(), 'slack', 'bugsnag'))
            ->report(EnhancedBackedEnum::class, 'KEY', null);

        $spy->shouldHaveReceived(
            'log',
            [
                'notice',
                "EnhancedBackedEnum does not have 'KEY'",
                [
                    'class' => EnhancedBackedEnum::class,
                    'key' => 'KEY'
                ]
            ]
        );
    }
}
