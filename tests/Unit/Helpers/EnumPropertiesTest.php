<?php

namespace Unit\Helpers;


use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableNonBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedEnum;
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
        EnumProperties::clear(ConstructableNonBackedEnum::class);
        EnumProperties::clear(EnhancedEnum::class);
    }

    public function providesTestcasesForStoreProperty(): array
    {
        return [
            'boolean' => ['property', true, true, ConstructableNonBackedEnum::class],
            'object' => ['anObject', new stdClass(), new stdClass(), ConstructableNonBackedEnum::class],
            'string' => ['aString', 'A String', 'A String', ConstructableNonBackedEnum::class],
            'enum' => ['anEnum', ConstructableNonBackedEnum::CALLABLE, ConstructableNonBackedEnum::CALLABLE, ConstructableNonBackedEnum::class],
            'callable' => ['property', fn() => 'true', fn() => 'true', ConstructableNonBackedEnum::class],

            'another-enum-that-tries-to-get' => ['anotherProperty', true, null, ConstructableNonBackedEnum::class, StringBackedMakersEnum::class],

        ];
    }

    public function testStoreShouldNotAcceptNonEnums()
    {
        $this->expectException(RuntimeException::class);

        EnumProperties::store(stdClass::class, 'property', 'value');
    }

    public function testGetShouldNotAcceptNonEnums()
    {
        $this->expectException(RuntimeException::class);

        EnumProperties::get(stdClass::class, 'property');
    }

    public function testClearShouldNotAcceptNonEnums()
    {
        $this->expectException(RuntimeException::class);

        EnumProperties::clear(stdClass::class);
    }

    public function providesTestcasesForStorePropertyGlobally(): array
    {
        return [
            'boolean' => ['property', true, true],
            'object' => ['anObject', new stdClass(), new stdClass()],
            'string' => ['aString', 'A String', 'A String'],
            'enum' => ['anEnum', ConstructableNonBackedEnum::CALLABLE, ConstructableNonBackedEnum::CALLABLE],
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
        EnumProperties::store(ConstructableNonBackedEnum::class, 'property', 'a value');
        EnumProperties::store(StringBackedMakersEnum::class, 'property', 'a value');

        EnumProperties::clear(ConstructableNonBackedEnum::class);

        $this->assertNull(EnumProperties::get(ConstructableNonBackedEnum::class, 'property'));
        $this->assertEquals('a value', EnumProperties::get(StringBackedMakersEnum::class, 'property'));
    }

    public function testClearsSingleProperty()
    {
        EnumProperties::store(ConstructableNonBackedEnum::class, 'property', 'a value');
        EnumProperties::store(ConstructableNonBackedEnum::class, 'property2', 'a value');

        EnumProperties::clear(ConstructableNonBackedEnum::class, 'property');

        $this->assertNull(EnumProperties::get(ConstructableNonBackedEnum::class, 'property'));
        $this->assertEquals('a value', EnumProperties::get(ConstructableNonBackedEnum::class, 'property2'));
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
        EnumProperties::store(ConstructableNonBackedEnum::class, 'property', 'local value');

        $this->assertEquals('local value', EnumProperties::get(ConstructableNonBackedEnum::class, 'property'));
    }
}
