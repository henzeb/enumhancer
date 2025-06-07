<?php

use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

uses(TestCase::class);

beforeEach(function () {
    Config::set('logging.default', 'stack');
});

test('should log', function () {
    $spy = \Mockery::spy(LoggerInterface::class);

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
});

test('should log with context', function () {
    $spy = \Mockery::spy(LoggerInterface::class);

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
});

test('should use different level', function () {
    $spy = \Mockery::spy(LoggerInterface::class);

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
});

test('should use different channel', function () {
    $spy = \Mockery::spy(LoggerInterface::class);

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
});

test('should use different channels', function () {
    $spy = \Mockery::spy(LoggerInterface::class);

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
});
