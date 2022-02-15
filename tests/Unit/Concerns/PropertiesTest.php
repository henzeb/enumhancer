<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;


use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedEnum;
use PHPUnit\Framework\TestCase;


class PropertiesTest extends TestCase
{
    public function testSetProperty()
    {
        EnhancedEnum::property('MyProperty', 'A Value');

        $this->assertEquals('A Value',EnumProperties::get(EnhancedEnum::class, 'MyProperty'));
    }

    public function testSetPropertyOverridesGlobal()
    {
        EnumProperties::global('MyProperty', 'A global Value');
        EnhancedEnum::property('MyProperty', 'A Value');

        $this->assertEquals('A Value',EnumProperties::get(EnhancedEnum::class, 'MyProperty'));
    }

    public function testGetProperty()
    {
        EnumProperties::clearGlobal();

        EnhancedEnum::property('MyProperty', 'A Value');

        $this->assertEquals('A Value', EnhancedEnum::property('MyProperty'));
    }

    public function testUnsetProperty()
    {
        EnumProperties::clearGlobal();
        EnhancedEnum::property('MyProperty', 'A Value');
        EnhancedEnum::unset('MyProperty');

        $this->assertNull(EnhancedEnum::property('MyProperty'));
    }

    public function testDoesNotUnsetGlobalProperty()
    {
        EnumProperties::clearGlobal();
        EnumProperties::store(EnhancedEnum::class, 'property', 'local property');
        EnumProperties::global( 'property', 'global property');

        EnhancedEnum::unset('property');

        $this->assertEquals('global property', EnhancedEnum::property('property'));

    }

    public function testUnsetAllLocalProperties()
    {
        EnumProperties::clearGlobal();
        EnhancedEnum::unsetAll();
        EnumProperties::global('globalProperty', 'a global property');
        EnhancedEnum::property('MyProperty', 'A Value');
        EnhancedEnum::property('MyProperty2', 'Another Value');
        EnhancedEnum::unsetAll();

        $this->assertEquals('a global property', EnhancedEnum::property('globalProperty'));
        $this->assertNull(EnhancedEnum::property('MyProperty'));
        $this->assertNull(EnhancedEnum::property('MyProperty2'));
    }
}
