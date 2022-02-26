<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;


use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use PHPUnit\Framework\TestCase;


class PropertiesTest extends TestCase
{
    public function testSetProperty()
    {
        EnhancedBackedEnum::property('MyProperty', 'A Value');

        $this->assertEquals('A Value',EnumProperties::get(EnhancedBackedEnum::class, 'MyProperty'));
    }

    public function testSetPropertyOverridesGlobal()
    {
        EnumProperties::global('MyProperty', 'A global Value');
        EnhancedBackedEnum::property('MyProperty', 'A Value');

        $this->assertEquals('A Value',EnumProperties::get(EnhancedBackedEnum::class, 'MyProperty'));
    }

    public function testGetProperty()
    {
        EnumProperties::clearGlobal();

        EnhancedBackedEnum::property('MyProperty', 'A Value');

        $this->assertEquals('A Value', EnhancedBackedEnum::property('MyProperty'));
    }

    public function testUnsetProperty()
    {
        EnumProperties::clearGlobal();
        EnhancedBackedEnum::property('MyProperty', 'A Value');
        EnhancedBackedEnum::unset('MyProperty');

        $this->assertNull(EnhancedBackedEnum::property('MyProperty'));
    }

    public function testDoesNotUnsetGlobalProperty()
    {
        EnumProperties::clearGlobal();
        EnumProperties::store(EnhancedBackedEnum::class, 'property', 'local property');
        EnumProperties::global( 'property', 'global property');

        EnhancedBackedEnum::unset('property');

        $this->assertEquals('global property', EnhancedBackedEnum::property('property'));

    }

    public function testUnsetAllLocalProperties()
    {
        EnumProperties::clearGlobal();
        EnhancedBackedEnum::unsetAll();
        EnumProperties::global('globalProperty', 'a global property');
        EnhancedBackedEnum::property('MyProperty', 'A Value');
        EnhancedBackedEnum::property('MyProperty2', 'Another Value');
        EnhancedBackedEnum::unsetAll();

        $this->assertEquals('a global property', EnhancedBackedEnum::property('globalProperty'));
        $this->assertNull(EnhancedBackedEnum::property('MyProperty'));
        $this->assertNull(EnhancedBackedEnum::property('MyProperty2'));
    }
}
