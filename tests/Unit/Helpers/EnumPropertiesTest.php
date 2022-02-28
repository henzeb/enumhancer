<?php

namespace Unit\Helpers;


use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedMakersEnum;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class EnumPropertiesTest extends TestCase
{
    protected function setUp(): void
    {
        EnumProperties::clearGlobal();
        EnumProperties::clear(StringBackedMakersEnum::class);
        EnumProperties::clear(ConstructableUnitEnum::class);
        EnumProperties::clear(EnhancedBackedEnum::class);
    }

    public function providesTestcasesForStoreProperty(): array
    {
        return [
            'boolean' => ['property', true, true, ConstructableUnitEnum::class],
            'object' => ['anObject', new stdClass(), new stdClass(), ConstructableUnitEnum::class],
            'string' => ['aString', 'A String', 'A String', ConstructableUnitEnum::class],
            'enum' => ['anEnum', ConstructableUnitEnum::CALLABLE, ConstructableUnitEnum::CALLABLE, ConstructableUnitEnum::class],
            'callable' => ['property', fn() => 'true', fn() => 'true', ConstructableUnitEnum::class],

            'another-enum-that-tries-to-get' => ['anotherProperty', true, null, ConstructableUnitEnum::class, StringBackedMakersEnum::class],

        ];
    }

    public function testStoreShouldNotAcceptNonEnums()
    {
        $this->expectError();

        EnumProperties::store(stdClass::class, 'property', 'value');
    }

    public function testGetShouldNotAcceptNonEnums()
    {
        $this->expectError();

        EnumProperties::get(stdClass::class, 'property');
    }

    public function testClearShouldNotAcceptNonEnums()
    {
        $this->expectError();

        EnumProperties::clear(stdClass::class);
    }

    public function providesTestcasesForStorePropertyGlobally(): array
    {
        return [
            'boolean' => ['property', true, true],
            'object' => ['anObject', new stdClass(), new stdClass()],
            'string' => ['aString', 'A String', 'A String'],
            'enum' => ['anEnum', ConstructableUnitEnum::CALLABLE, ConstructableUnitEnum::CALLABLE],
            'callable' => ['property', fn() => 'true', fn() => 'true'],
        ];
    }

    /**
     * @return void
     *
     * @dataProvider providesTestcasesForStoreProperty
     */
    public function testStoreProperty(string $key, mixed $value, mixed $expectedValue, string $storeIn, string $expectedStoreIn = null)
    {
        EnumProperties::store($storeIn, $key, $value);

        $this->assertEquals(
            $expectedValue,
            EnumProperties::get($expectedStoreIn ?? $storeIn, $key)
        );
    }

    public function testClearsProperties()
    {
        EnumProperties::store(ConstructableUnitEnum::class, 'property', 'a value');
        EnumProperties::store(StringBackedMakersEnum::class, 'property', 'a value');

        EnumProperties::clear(ConstructableUnitEnum::class);

        $this->assertNull(EnumProperties::get(ConstructableUnitEnum::class, 'property'));
        $this->assertEquals('a value', EnumProperties::get(StringBackedMakersEnum::class, 'property'));
    }

    public function testClearsSingleProperty()
    {
        EnumProperties::store(ConstructableUnitEnum::class, 'property', 'a value');
        EnumProperties::store(ConstructableUnitEnum::class, 'property2', 'a value');

        EnumProperties::clear(ConstructableUnitEnum::class, 'property');

        $this->assertNull(EnumProperties::get(ConstructableUnitEnum::class, 'property'));
        $this->assertEquals('a value', EnumProperties::get(ConstructableUnitEnum::class, 'property2'));
    }

    public function testClearsGlobal()
    {
        EnumProperties::global('globalProperty', 'a value');

        EnumProperties::clearGlobal();

        $this->assertNull(EnumProperties::get(StringBackedMakersEnum::class, 'globalProperty'));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $expectedValue
     * @return void
     *
     * @dataProvider providesTestcasesForStorePropertyGlobally
     */
    public function testStoreGlobally(string $key, mixed $value, mixed $expectedValue)
    {

        EnumProperties::global($key, $value);

        $this->assertEquals(
            $expectedValue,
            EnumProperties::get(StringBackedMakersEnum::class, $key)
        );
    }

    public function testIfLocalPropertyOverridesGlobalProperty()
    {
        EnumProperties::global('property', 'global value');
        EnumProperties::store(ConstructableUnitEnum::class, 'property', 'local value');

        $this->assertEquals('local value', EnumProperties::get(ConstructableUnitEnum::class, 'property'));
    }
}
