<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\ConfigureState;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Configure\ConfigureEnum;
use Henzeb\Enumhancer\Tests\Helpers\ClearsEnumProperties;
use PHPUnit\Framework\TestCase;

class ConfigureStateTest extends TestCase
{
    use ClearsEnumProperties;

    public function testSetTransitionHook()
    {
        $hook = new class extends TransitionHook {
            public function allowsConfiguredNotConfigured(): bool
            {
                return false;
            }
        };

        ConfigureEnum::setTransitionHook($hook);

        $this->assertSame($hook, EnumProperties::get(ConfigureEnum::class, EnumProperties::reservedWord('hooks')));

        $this->assertFalse(
            ConfigureEnum::Configured->isTransitionAllowed(ConfigureEnum::NotConfigured)
        );

    }

    public function testSetTransitionHookOnce()
    {
        $hook = new class extends TransitionHook {
            public function allowsConfiguredNotConfigured(): bool
            {
                return false;
            }
        };

        ConfigureEnum::setTransitionHookOnce($hook);

        $this->assertSame($hook, EnumProperties::get(ConfigureEnum::class, EnumProperties::reservedWord('hooks')));

        $this->assertFalse(ConfigureEnum::Configured->isTransitionAllowed(ConfigureEnum::NotConfigured));

        $this->expectException(PropertyAlreadyStoredException::class);

        ConfigureEnum::setTransitionHook(new class extends TransitionHook{});
    }

    public function testSetTransitions()
    {
        $expected = [
            ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
            ConfigureEnum::Configured->name => ConfigureEnum::NotConfigured,
        ];


        ConfigureEnum::setTransitions([
            ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
        ]);

        $this->assertEquals($expected, ConfigureEnum::transitions());
    }

    public function testSetTransitionsOnce()
    {
        $expected = [
            ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
            ConfigureEnum::Configured->name => ConfigureEnum::NotConfigured,
        ];


        ConfigureEnum::setTransitionsOnce([
            ConfigureEnum::NotConfigured->name => ConfigureEnum::Configured,
        ]);

        $this->assertEquals($expected, ConfigureEnum::transitions());

        $this->expectException(PropertyAlreadyStoredException::class);

        ConfigureEnum::setTransitions([]);
    }
}
