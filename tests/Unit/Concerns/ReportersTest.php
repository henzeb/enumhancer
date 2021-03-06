<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;


use BackedEnum;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Tests\Fixtures\CustomReportingEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\NotReportingEnum;
use Henzeb\Enumhancer\Tests\Fixtures\ReporterTestEnum;

use Mockery;
use PHPUnit\Framework\TestCase;

class ReportersTest extends TestCase
{
    public function providesEnumsToTestWith(): array
    {
        return [
            'just-reporters' => [ReporterTestEnum::class],
            'with-mappers-enhancment' => [EnhancedBackedEnum::class]
        ];
    }


    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testExistingEnum(string $enum)
    {
        $this->assertEquals(
            $enum::ENUM,
            $enum::makeOrReport('ENUM')
        );
    }

    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testNoreporting(string $enum)
    {
        $this->assertNull($enum::makeOrReport('NOT EXIST'));
    }

    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testDoesReport(string $enum)
    {
        $reporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->once()->getMock();

        EnumReporter::set(
            $reporter
        );

        $this->assertNull($enum::makeOrReport('NOT EXIST'));
    }

    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testOverridesGlobalReporterWithNull(string $enum)
    {
        $globalReporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->never()->getMock();

        EnumReporter::set(
            $globalReporter
        );

        $this->assertNull(NotReportingEnum::makeOrReport('NOT EXIST'));
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

        $this->assertNull(CustomReportingEnum::makeOrReport('NOT EXIST'));
    }

    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testExistingEnums(string $enum)
    {
        $this->assertEquals(
            [$enum::ENUM, $enum::ANOTHER_ENUM],
            $enum::makeOrReportArray(['ENUM', 'ANOTHER_ENUM'])
        );
    }

    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testNonExistingEnums(string $enum)
    {
        $globalReporter = Mockery::mock(Reporter::class)
            ->shouldReceive(
                'report'
            )->once()->getMock();

        EnumReporter::set($globalReporter);

        $this->assertEquals(
            [$enum::ENUM],
            $enum::makeOrReportArray(['ENUM', 'DOESNOTEXIST'])
        );
    }

    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testReportWithContext(string $enum)
    {
        $reporter = new class implements Reporter {

            public function report(string $enum, ?string $key, ?BackedEnum $context): void
            {
                enum_exists($context::class);
            }
        };

        EnumReporter::set($reporter);

        $this->assertNull($enum::makeOrReport('DOESNOTEXIST', EnhancedBackedEnum::ANOTHER_ENUM));

    }

    /**
     * @param string|Enhancers $enum
     * @return void
     *
     * @dataProvider providesEnumsToTestWith
     */
    public function testMakeOrReportArrayWithContext(string $enum)
    {
        $reporter = new class implements Reporter {

            public function report(string $enum, ?string $key, ?BackedEnum $context): void
            {
                enum_exists($context::class);
            }
        };

        EnumReporter::set($reporter);

        $this->assertEquals([], $enum::makeOrReportArray(['DOESNOTEXIST'], EnhancedBackedEnum::ANOTHER_ENUM));

    }
}
