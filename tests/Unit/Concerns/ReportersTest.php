<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;


use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Tests\Fixtures\CustomReportingEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\NotReportingEnum;
use Henzeb\Enumhancer\Tests\Fixtures\ReporterTestEnum;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ReportersTest extends TestCase
{
    public static function providesEnumsToTestWith(): array
    {
        return [
            'just-reporters' => [ReporterTestEnum::class],
            'with-mappers-enhancement' => [EnhancedBackedEnum::class]
        ];
    }


    #[DataProvider("providesEnumsToTestWith")]
    public function testExistingEnum(string $enum)
    {
        $this->assertEquals(
            $enum::ENUM,
            $enum::getOrReport('ENUM')
        );
    }

    #[DataProvider("providesEnumsToTestWith")]
    public function testNoreporting(string $enum)
    {
        $this->assertNull($enum::getOrReport('NOT EXIST'));
    }

    #[DataProvider("providesEnumsToTestWith")]
    public function testDoesReport(string $enum)
    {
        $reporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->once()->getMock();

        EnumReporter::set(
            $reporter
        );

        $this->assertNull($enum::getOrReport('NOT EXIST'));
    }

    #[DataProvider("providesEnumsToTestWith")]
    public function testOverridesGlobalReporterWithNull(string $enum)
    {
        $globalReporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->never()->getMock();

        EnumReporter::set(
            $globalReporter
        );

        $this->assertNull(NotReportingEnum::getOrReport('NOT EXIST'));
    }

    public function testOverridesGlobalReporterWithOwnReporter()
    {
        $globalReporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->never()->getMock();

        $customReporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->once()->getMock();

        EnumReporter::set(
            $globalReporter
        );

        CustomReportingEnum::property('reporter', $customReporter);

        $this->assertNull(CustomReportingEnum::getOrReport('NOT EXIST'));
    }

    #[DataProvider("providesEnumsToTestWith")]
    public function testExistingEnums(string $enum)
    {
        $this->assertEquals(
            [$enum::ENUM, $enum::ANOTHER_ENUM],
            $enum::getOrReportArray(['ENUM', 'ANOTHER_ENUM'])
        );
    }

    #[DataProvider("providesEnumsToTestWith")]
    public function testNonExistingEnums(string $enum)
    {
        $globalReporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->once()->getMock();

        EnumReporter::set($globalReporter);

        $this->assertEquals(
            [$enum::ENUM],
            $enum::getOrReportArray(['ENUM', 'DOESNOTEXIST'])
        );
    }

    #[DataProvider("providesEnumsToTestWith")]
    public function testReportWithContext(string $enum)
    {
        $reporter = new class implements Reporter {

            public function report(string $enum, ?string $key, ?BackedEnum $context): void
            {
                enum_exists($context::class);
            }
        };

        EnumReporter::set($reporter);

        $this->assertNull($enum::getOrReport('DOESNOTEXIST', EnhancedBackedEnum::ANOTHER_ENUM));

    }

    #[DataProvider("providesEnumsToTestWith")]
    public function testGetOrReportArrayWithContext(string $enum)
    {
        $reporter = new class implements Reporter {

            public function report(string $enum, ?string $key, ?BackedEnum $context): void
            {
                enum_exists($context::class);
            }
        };

        EnumReporter::set($reporter);

        $this->assertEquals([], $enum::getOrReportArray(['DOESNOTEXIST'], EnhancedBackedEnum::ANOTHER_ENUM));

    }

    protected function tearDown(): void
    {
        EnumReporter::set(null);
    }
}
