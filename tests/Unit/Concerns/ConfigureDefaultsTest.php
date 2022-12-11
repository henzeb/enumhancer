<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Helpers\ClearsEnumProperties;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;

class ConfigureDefaultsTest extends TestCase
{
    use ClearsEnumProperties;

    public function testSetDefault()
    {
        DefaultsEnum::setDefault(DefaultsEnum::Enum);

        $this->assertEquals(
            DefaultsEnum::Enum,
            EnumProperties::get(DefaultsEnum::class, EnumProperties::reservedWord('defaults'))
        );

        $this->assertEquals(
            DefaultsEnum::Enum,
            DefaultsEnum::default()
        );
    }

    public function testSetDefaultOnce()
    {
        DefaultsEnum::setDefaultOnce(DefaultsEnum::Enum);

        $this->assertEquals(
            DefaultsEnum::Enum,
            EnumProperties::get(DefaultsEnum::class, EnumProperties::reservedWord('defaults'))
        );

        $this->assertEquals(
            DefaultsEnum::Enum,
            DefaultsEnum::default()
        );

        $this->expectException(PropertyAlreadyStoredException::class);

        DefaultsEnum::setDefault(DefaultsEnum::Default);
    }
}
