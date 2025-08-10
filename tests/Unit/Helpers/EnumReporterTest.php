<?php

use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

beforeEach(function () {
    $this->app->getProviders(EnumhancerServiceProvider::class);
});

test('reporter not set', function () {
    EnumReporter::set(null);
    expect(EnumReporter::get())->toBeNull();
});

test('set reporter', function () {
    EnumReporter::set(null);

    $reporter = new class implements Reporter {
        public function report(string $enum, ?string $key, ?\BackedEnum $context): void
        {
        }
    };

    EnumReporter::set($reporter);

    expect(EnumReporter::get())->toBe($reporter);
});

test('set string reporter', function () {
    EnumReporter::set(null);
    EnumReporter::set(LaravelLogReporter::class);

    expect(EnumReporter::get())->toEqual(new LaravelLogReporter());
});

test('set null', function () {
    EnumReporter::set(null);

    expect(EnumReporter::get())->toBeNull();
});

test('is not a reporter', function () {
    EnumReporter::set(stdClass::class);
})->throws(RuntimeException::class);

test('object is not a reporter', function () {
    EnumReporter::set(new stdClass());
})->throws(TypeError::class);

test('set laravel reporter', function () {
    EnumReporter::set(null);
    EnumReporter::laravel();

    expect(EnumReporter::get()::class)->toBe(LaravelLogReporter::class);
});

test('set laravel reporter with different log level', function () {
    EnumReporter::set(null);
    EnumReporter::laravel(LogLevel::Alert);

    $spy = \Mockery::spy(LoggerInterface::class);

    Log::shouldReceive('stack')
        ->once()
        ->with(['stack'])
        ->andReturn($spy);

    EnumReporter::get()->report(LogLevel::class, null, null);

    $spy->shouldHaveReceived(
        'log',
        [
            'alert',
            'LogLevel: A null value was passed',
            [
                'class' => LogLevel::class,
            ]
        ]
    );
});

test('set laravel reporter with different channels', function () {
    EnumReporter::set(null);
    EnumReporter::laravel(null, 'bugsnag', 'daily');

    $spy = Mockery::spy(LoggerInterface::class);

    Log::shouldReceive('stack')
        ->once()
        ->with(['bugsnag', 'daily'])
        ->andReturn($spy);

    EnumReporter::get()->report(LogLevel::class, null, null);

    $spy->shouldHaveReceived(
        'log',
        [
            'notice',
            'LogLevel: A null value was passed',
            [
                'class' => LogLevel::class,
            ]
        ]
    );
});

test('make or report with unit enum', function () {
    $mock = \Mockery::mock(Reporter::class);
    $mock->expects('report')
        ->with(EnhancedBackedEnum::class, 'Unique', null);

    EnumReporter::getOrReport(EnhancedBackedEnum::class, EnhancedUnitEnum::Unique, null, $mock);
});

test('make or report should error with non enum', function () {
    EnumReporter::getOrReport(stdClass::class, '', null, new LaravelLogReporter());
})->throws(TypeError::class);

test('make or report array should error with non enum', function () {
    EnumReporter::getOrReportArray(stdClass::class, [], null, new LaravelLogReporter());
})->throws(TypeError::class);
