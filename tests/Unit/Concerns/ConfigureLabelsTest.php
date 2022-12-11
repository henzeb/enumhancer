<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Helpers\ClearsEnumProperties;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Configure\ConfigureEnum;

class ConfigureLabelsTest extends TestCase
{
    use ClearsEnumProperties;

    public function testSetLabels()
    {
        $expected = [
            ConfigureEnum::Configured->name => 'Yes',
            ConfigureEnum::NotConfigured->name => 'No'
        ];

        ConfigureEnum::setLabels($expected);

        $this->assertEquals($expected, ConfigureEnum::labels());

        $this->assertEquals($expected, ConfigureEnum::property(EnumProperties::reservedWord('labels')));

        $this->assertEquals('Yes', ConfigureEnum::Configured->label());
    }

    public function testSetLabelsOnce()
    {
        $expected = [
            ConfigureEnum::Configured->name => 'Yes',
            ConfigureEnum::NotConfigured->name => 'No'
        ];

        ConfigureEnum::setLabelsOnce($expected);

        $this->assertEquals($expected, ConfigureEnum::labels());

        $this->assertEquals('Yes', ConfigureEnum::Configured->label());

        $this->assertEquals($expected, ConfigureEnum::property(EnumProperties::reservedWord('labels')));

        $this->expectException(PropertyAlreadyStoredException::class);

        ConfigureEnum::setLabels([]);
    }
}
