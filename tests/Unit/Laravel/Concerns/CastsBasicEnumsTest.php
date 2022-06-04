<?php

namespace Unit\Laravel\Concerns;


use UnitEnum;
use ValueError;
use Orchestra\Testbench\TestCase;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\CastsBasicEnumsModel;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedMakersEnum;
use Henzeb\Enumhancer\Tests\Fixtures\CastsBasicEnumsLowerCaseModel;

class CastsBasicEnumsTest extends TestCase
{
    public function providesEnums()
    {
        return [
            [SubsetUnitEnum::ENUM, 'unitEnum'],
            [IntBackedEnum::TEST, 'intBackedEnum'],
            [StringBackedMakersEnum::TEST, 'stringBackedEnum'],

            [SubsetUnitEnum::ENUM, 'unitEnum', false],
            [IntBackedEnum::TEST, 'intBackedEnum', false],
            [StringBackedMakersEnum::TEST, 'stringBackedEnum', false],
        ];
    }

    /**
     * @return void
     *
     * @dataProvider providesEnums
     */
    public function testShouldCastCorrectlyFromString(UnitEnum $enum, string $key, bool $keepCase = true)
    {
        $model = $keepCase ? new CastsBasicEnumsModel() : new CastsBasicEnumsLowerCaseModel();
        $model->setRawAttributes([
            $key => EnumValue::value($enum, $keepCase)
        ]);

        $this->assertEquals(
            $enum,
            $model->$key,
        );
    }

    /**
     * @return void
     *
     * @dataProvider providesEnums
     */
    public function testShouldCastCorrectlyToString(UnitEnum $enum, string $key, bool $keepCase = true)
    {
        $model = $keepCase ? new CastsBasicEnumsModel() : new CastsBasicEnumsLowerCaseModel();
        $model->$key = $enum;

        $this->assertEquals(
            EnumValue::value($enum, $keepCase),
            $model->toArray()[$key],
        );
    }

    public function testShouldHandleNull() {
        $model = new CastsBasicEnumsModel();
        $model->unitEnum = null;

        $this->assertEquals(null, $model->unitEnum);
    }

    public function testShouldHandleObjectInAttribute() {
        $model = new CastsBasicEnumsModel();
        $model->setRawAttributes(['unitEnum'=>SubsetUnitEnum::ENUM]);

        $this->assertEquals(SubsetUnitEnum::ENUM, $model->unitEnum);
    }

    public function testShouldHandleStringValue() {
        $model = new CastsBasicEnumsModel();
        $model->unitEnum = 'enum';

        $this->assertEquals('ENUM', $model->getAttributes()['unitEnum']);

        $this->assertEquals(SubsetUnitEnum::ENUM, $model->unitEnum);
    }

    public function testShouldHandleStringValueLowerCase() {
        $model = new CastsBasicEnumsLowerCaseModel();
        $model->unitEnum = 'ENUM';

        $this->assertEquals('enum', $model->getAttributes()['unitEnum']);
    }

    public function testShouldFailIfStringIsNotValid() {
        $this->expectException(ValueError::class);
        $model = new CastsBasicEnumsModel();
        $model->unitEnum = 'NotAnEnum';
    }

    public function testShouldFailIfEnumIsNotValid() {
        $this->expectException(ValueError::class);

        $model = new CastsBasicEnumsModel();
        $model->unitEnum = IntBackedEnum::TEST;
    }
}
