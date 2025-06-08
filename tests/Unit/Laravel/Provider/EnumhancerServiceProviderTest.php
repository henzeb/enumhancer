<?php

use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;
use Henzeb\Enumhancer\Tests\TestCase;

uses(TestCase::class);

test('has set laravel reporter', function () {
    $reporter = EnumReporter::get();
    expect($reporter)->not()->toBeNull();
    expect($reporter::class)->toBe(LaravelLogReporter::class);
});

test('has set default log level', function () {
    expect(LogLevel::default())->toBe(LogLevel::Notice);
});
