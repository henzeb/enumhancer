<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use Mockery;
use stdClass;
use BackedEnum;
use RuntimeException;
use Psr\Log\LoggerInterface;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Log;
use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;


class EnumReporterTest extends TestCase
{
    public function testReporterNotSet()
    {
        EnumReporter::set(null);
        $this->assertNull(EnumReporter::get());
    }

    public function testSetReporter()
    {
        EnumReporter::set(null);

        $reporter = new class implements Reporter {
            public function report(string $enum, ?string $key, ?BackedEnum $context): void
            {
            }
        };

        EnumReporter::set($reporter);

        $this->assertEquals($reporter, EnumReporter::get());
    }

    public function testSetStringReporter()
    {
        EnumReporter::set(null);
        EnumReporter::set(LaravelLogReporter::class);

        $this->assertEquals(new LaravelLogReporter(), EnumReporter::get());
    }

    public function testSetNull()
    {

        EnumReporter::set(null);

        $this->assertEquals(null, EnumReporter::get());
    }

    public function testIsNotAReporter()
    {

        $this->expectException(RuntimeException::class);

        EnumReporter::set(stdClass::class);
    }

    public function testObjectIsNotAReporter()
    {

        $this->expectError();

        EnumReporter::set(new stdClass());
    }

    public function testSetLaravelReporter()
    {
        EnumReporter::set(null);
        EnumReporter::laravel();

        $this->assertEquals(LaravelLogReporter::class, EnumReporter::get()::class);
    }

    public function testSetLaravelReporterWithDifferentLogLevel()
    {
        EnumReporter::set(null);
        EnumReporter::laravel(LogLevel::Alert);

        $spy = Mockery::spy(LoggerInterface::class);

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
    }

    public function testSetLaravelReporterWithDifferenChannels()
    {
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
    }

    public function testMakeOrReportShouldErrorWithNonEnum()
    {
        $this->expectError();
        EnumReporter::makeOrReport(stdClass::class, '', null, new LaravelLogReporter());
    }

    public function testMakeOrReportArrayShouldErrorWithNonEnum()
    {
        $this->expectError();
        EnumReporter::makeOrReportArray(stdClass::class, [], null, new LaravelLogReporter());
    }
}
