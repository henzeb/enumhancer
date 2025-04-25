<?php

namespace Henzeb\Enumhancer\Tests\Unit\Helpers;


use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Tests\Fixtures\ConstructableUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Helpers\ClearsEnumProperties;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

class EnumPropertiesTest extends TestCase
{
    use ClearsEnumProperties;

    public static function providesTestcasesForStoreProperty(): array
    {
        return [
            'boolean' => ['property', true, true, ConstructableUnitEnum::class],
            'object' => ['anObject', new stdClass(), new stdClass(), ConstructableUnitEnum::class],
            'string' => ['aString', 'A String', 'A String', ConstructableUnitEnum::class],
            'enum' => [
                'anEnum',
                ConstructableUnitEnum::CALLABLE,
                ConstructableUnitEnum::CALLABLE,
                ConstructableUnitEnum::class
            ],
            'callable' => ['property', fn() => 'true', fn() => 'true', ConstructableUnitEnum::class],

            'another-enum-that-tries-to-get' => [
                'anotherProperty',
                true,
                null,
                ConstructableUnitEnum::class,
                StringBackedGetEnum::class
            ],

        ];
    }

    public function testStoreShouldNotAcceptNonEnums()
    {
        $this->expectException(TypeError::class);

        EnumProperties::store(stdClass::class, 'property', 'value');
    }

    public function testGetShouldNotAcceptNonEnums()
    {
        $this->expectException(TypeError::class);

        EnumProperties::get(stdClass::class, 'property');
    }

    public function testClearShouldNotAcceptNonEnums()
    {
        $this->expectException(TypeError::class);

        EnumProperties::clear(stdClass::class);
    }

    public static function providesTestcasesForStorePropertyGlobally(): array
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
    public function testStoreProperty(
        string $key,
        mixed $value,
        mixed $expectedValue,
        string $storeIn,
        ?string $expectedStoreIn = null
    ) {
        EnumProperties::store($storeIn, $key, $value);

        $this->assertEquals(
            $expectedValue,
            EnumProperties::get($expectedStoreIn ?? $storeIn, $key)
        );
    }

    /**
     * @return void
     *
     * @dataProvider providesTestcasesForStoreProperty
     */
    public function testStorePropertyOnce(
        string $key,
        mixed $value,
        mixed $expectedValue,
        string $storeIn,
        ?string $expectedStoreIn = null
    ) {
        EnumProperties::storeOnce($storeIn, $key, $value);

        $this->assertEquals(
            $expectedValue,
            EnumProperties::get($expectedStoreIn ?? $storeIn, $key)
        );

        $this->expectException(PropertyAlreadyStoredException::class);

        EnumProperties::storeOnce($storeIn, $key, $value);

    }

    /**
     * @return void
     *
     * @dataProvider providesTestcasesForStoreProperty
     */
    public function testStorePropertyOnceAndTryStoring(
        string $key,
        mixed $value,
        mixed $expectedValue,
        string $storeIn,
        ?string $expectedStoreIn = null
    ) {
        EnumProperties::storeOnce($storeIn, $key, $value);

        $this->assertEquals(
            $expectedValue,
            EnumProperties::get($expectedStoreIn ?? $storeIn, $key)
        );

        $this->expectException(PropertyAlreadyStoredException::class);

        EnumProperties::store($storeIn, $key, $value);

    }

    public function testClearsProperties()
    {
        EnumProperties::store(ConstructableUnitEnum::class, 'property', 'a value');
        EnumProperties::store(StringBackedGetEnum::class, 'property', 'a value');

        EnumProperties::clear(ConstructableUnitEnum::class);

        $this->assertNull(EnumProperties::get(ConstructableUnitEnum::class, 'property'));
        $this->assertEquals('a value', EnumProperties::get(StringBackedGetEnum::class, 'property'));
    }

    public function testDoesntClearPropertiesOnce()
    {
        EnumProperties::storeOnce(ConstructableUnitEnum::class, 'property', 'a value');
        EnumProperties::storeOnce(ConstructableUnitEnum::class, 'property2', 'a value');

        EnumProperties::clear(ConstructableUnitEnum::class);

        $this->assertEquals('a value', EnumProperties::get(ConstructableUnitEnum::class, 'property'));
        $this->assertEquals('a value', EnumProperties::get(ConstructableUnitEnum::class, 'property2'));
    }

    public function testClearsSingleProperty()
    {
        EnumProperties::store(ConstructableUnitEnum::class, 'property', 'a value');
        EnumProperties::store(ConstructableUnitEnum::class, 'property2', 'a value');

        EnumProperties::clear(ConstructableUnitEnum::class, 'property');

        $this->assertNull(EnumProperties::get(ConstructableUnitEnum::class, 'property'));
        $this->assertEquals('a value', EnumProperties::get(ConstructableUnitEnum::class, 'property2'));
    }

    public function testDoesntClearSinglePropertyOnce()
    {
        EnumProperties::storeOnce(ConstructableUnitEnum::class, 'property', 'a value');

        EnumProperties::clear(ConstructableUnitEnum::class, 'property');

        $this->assertEquals('a value', EnumProperties::get(ConstructableUnitEnum::class, 'property'));
    }

    public function testClearsGlobal()
    {
        EnumProperties::global('globalProperty', 'a value');

        EnumProperties::clearGlobal();

        $this->assertNull(EnumProperties::get(StringBackedGetEnum::class, 'globalProperty'));
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
            EnumProperties::get(StringBackedGetEnum::class, $key)
        );
    }

    public function testIfLocalPropertyOverridesGlobalProperty()
    {
        EnumProperties::global('property', 'global value');
        EnumProperties::store(ConstructableUnitEnum::class, 'property', 'local value');

        $this->assertEquals('local value', EnumProperties::get(ConstructableUnitEnum::class, 'property'));
    }

    public function testStoreOnceOverridesStore()
    {
        EnumProperties::store(ConstructableUnitEnum::class, 'test', 'test');
        EnumProperties::storeOnce(ConstructableUnitEnum::class, 'test', 'something else');

        $this->assertEquals('something else', EnumProperties::get(ConstructableUnitEnum::class, 'test'));
    }

    public static function providesReservedWords(): array
    {
        return [
            ['@default_configure', 'defaults'],
            ['@labels_configure', 'labels'],
            ['@mapper_configure', 'mapper'],
            ['@state_configure', 'state'],
            ['@state_hook_configure', 'hooks'],
        ];
    }

    /**
     * @return void
     * @dataProvider providesReservedWords
     */
    public function testReservedWordsMapping(string $expected, string $name)
    {
        $this->assertEquals($expected, EnumProperties::reservedWord($name));
    }

    /**
     * @return void
     * @dataProvider providesReservedWords
     */
    public function testReservedWordsWhenTryingToStore(string $name)
    {
        $this->expectException(ReservedPropertyNameException::class);
        EnumProperties::store(ConstructableUnitEnum::class, $name, 'test');
    }

    /**
     * @return void
     * @dataProvider providesReservedWords
     */
    public function testReservedWordsWhenTryingToStoreOnce(string $name)
    {
        $this->expectException(ReservedPropertyNameException::class);
        EnumProperties::storeOnce(ConstructableUnitEnum::class, $name, 'test');
    }
}
