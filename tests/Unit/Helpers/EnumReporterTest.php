<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class EnumReporterTest extends TestCase
{
    public function testReporterNotSet() {
        EnumReporter::set(null);
        $this->assertNull(EnumReporter::get());
    }

    public function testSetReporter() {
        EnumReporter::set(null);

        $reporter = new class implements Reporter {
            public function report(string $enum, ?string $key, ?BackedEnum $context): void {}
        };

        EnumReporter::set($reporter);

        $this->assertEquals($reporter, EnumReporter::get());
    }

    public function testSetStringReporter() {
        EnumReporter::set(null);
        EnumReporter::set(LaravelLogReporter::class);

        $this->assertEquals(new LaravelLogReporter(), EnumReporter::get());
    }

    public function testSetNull() {

        EnumReporter::set(null);

        $this->assertEquals(null, EnumReporter::get());
    }

    public function testIsNotAReporter() {

        $this->expectException(RuntimeException::class);

        EnumReporter::set(stdClass::class);
    }

    public function testObjectIsNotAReporter() {

        $this->expectError();

        EnumReporter::set(new stdClass());
    }

    public function testSetLaravelReporter()
    {
        EnumReporter::set(null);
        EnumReporter::laravel();

        $this->assertEquals(LaravelLogReporter::class, EnumReporter::get()::class);
    }

    public function testMakeOrReportShouldErrorWithNonEnum() {
        $this->expectError();
        EnumReporter::makeOrReport(stdClass::class, '', null, new LaravelLogReporter());
    }

    public function testMakeOrReportArrayShouldErrorWithNonEnum() {
        $this->expectError();
        EnumReporter::makeOrReportArray(stdClass::class, [], null, new LaravelLogReporter());
    }
}
