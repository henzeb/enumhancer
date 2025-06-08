<?php

use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Tests\Fixtures\CustomReportingEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\NotReportingEnum;
use Henzeb\Enumhancer\Tests\Fixtures\ReporterTestEnum;

afterEach(function () {
    EnumReporter::set(null);
});

test('existing enum', function (string $enum) {
    expect($enum::getOrReport('ENUM'))->toBe($enum::ENUM);
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);

test('no reporting', function (string $enum) {
    expect($enum::getOrReport('NOT EXIST'))->toBeNull();
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);

test('does report', function (string $enum) {
    $reporter = \Mockery::mock(Reporter::class)
        ->shouldReceive(
            'report'
        )->once()->getMock();

    EnumReporter::set(
        $reporter
    );

    expect($enum::getOrReport('NOT EXIST'))->toBeNull();
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);

test('overrides global reporter with null', function (string $enum) {
    $globalReporter = \Mockery::mock(Reporter::class)
        ->shouldReceive(
            'report'
        )->never()->getMock();

    EnumReporter::set(
        $globalReporter
    );

    expect(NotReportingEnum::getOrReport('NOT EXIST'))->toBeNull();
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);

test('overrides global reporter with own reporter', function () {
    $globalReporter = Mockery::mock(Reporter::class)
        ->shouldReceive(
            'report'
        )->never()->getMock();

    $customReporter = \Mockery::mock(Reporter::class)
        ->shouldReceive(
            'report'
        )->once()->getMock();

    EnumReporter::set(
        $globalReporter
    );

    CustomReportingEnum::property('reporter', $customReporter);

    expect(CustomReportingEnum::getOrReport('NOT EXIST'))->toBeNull();
});

test('existing enums', function (string $enum) {
    expect($enum::getOrReportArray(['ENUM', 'ANOTHER_ENUM']))->toBe([$enum::ENUM, $enum::ANOTHER_ENUM]);
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);

test('non existing enums', function (string $enum) {
    $globalReporter = Mockery::mock(Reporter::class)
        ->shouldReceive(
            'report'
        )->once()->getMock();

    EnumReporter::set($globalReporter);

    expect($enum::getOrReportArray(['ENUM', 'DOESNOTEXIST']))->toBe([$enum::ENUM]);
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);

test('report with context', function (string $enum) {
    $reporter = new class implements Reporter {

        public function report(string $enum, ?string $key, ?\BackedEnum $context): void
        {
            enum_exists($context::class);
        }
    };

    EnumReporter::set($reporter);

    expect($enum::getOrReport('DOESNOTEXIST', EnhancedBackedEnum::ANOTHER_ENUM))->toBeNull();
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);

test('get or report array with context', function (string $enum) {
    $reporter = new class implements Reporter {

        public function report(string $enum, ?string $key, ?\BackedEnum $context): void
        {
            enum_exists($context::class);
        }
    };

    EnumReporter::set($reporter);

    expect($enum::getOrReportArray(['DOESNOTEXIST'], EnhancedBackedEnum::ANOTHER_ENUM))->toBe([]);
})->with([
    'just-reporters' => [ReporterTestEnum::class],
    'with-mappers-enhancement' => [EnhancedBackedEnum::class]
]);