<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Helpers\ClearsEnumProperties;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Configure\ConfigureEnum;

class ConfigureMapperTest extends TestCase
{
    use ClearsEnumProperties;

    public function testSetMapper()
    {
        $mapper = new class extends Mapper {

            protected function mappable(): array
            {
                return [
                    'set' => ConfigureEnum::Configured
                ];
            }
        };

        ConfigureEnum::setMapper($mapper);

        $this->assertSame([$mapper], ConfigureEnum::property(EnumProperties::reservedWord('mapper')));

        $this->assertEquals(ConfigureEnum::Configured, ConfigureEnum::get('set'));
    }

    public function testSetMapperOnce()
    {
        $mapper = new class extends Mapper {

            protected function mappable(): array
            {
                return [
                    'set' => ConfigureEnum::Configured
                ];
            }
        };

        ConfigureEnum::setMapperOnce($mapper);

        $this->assertSame([$mapper], ConfigureEnum::property(EnumProperties::reservedWord('mapper')));

        $this->assertEquals(ConfigureEnum::Configured, ConfigureEnum::get('set'));

        $this->expectException(PropertyAlreadyStoredException::class);

        ConfigureEnum::setMapper(new class extends Mapper {
            protected function mappable(): array
            {
                return [];
            }
        });
    }
}
