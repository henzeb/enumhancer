<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Concerns;


use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Exceptions\IllegalEnumTransitionException;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Laravel\Concerns\CastsStatefulEnumerations;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBasicEnumsLowerCaseModel;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBasicEnumsModel;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsStatefulEnumsLowerCaseModel;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsStatefulEnumsModel;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use UnitEnum;
use ValueError;

class CastsStatefulEnumerationsTest extends TestCase
{
    public static function providesEnums(): array
    {
        return [
            [SubsetUnitEnum::ENUM, 'unitEnum'],
            [IntBackedEnum::TEST, 'intBackedEnum'],
            [StringBackedGetEnum::TEST, 'stringBackedEnum'],

            [SubsetUnitEnum::ENUM, 'unitEnum', false],
            [IntBackedEnum::TEST, 'intBackedEnum', false],
            [StringBackedGetEnum::TEST, 'stringBackedEnum', false],
        ];
    }

    #[DataProvider("providesEnums")]
    public function testShouldCastCorrectlyFromString(UnitEnum $enum, string $key, bool $keepCase = true)
    {
        $model = $keepCase ? new CastsStatefulEnumsModel() : new CastsStatefulEnumsLowerCaseModel();
        $model->setRawAttributes([
            $key => EnumValue::value($enum, $keepCase)
        ]);

        $this->assertEquals(
            $enum,
            $model->$key,
        );
    }

    #[DataProvider("providesEnums")]
    public function testShouldCastCorrectlyToString(UnitEnum $enum, string $key, bool $keepCase = true)
    {
        $model = $keepCase ? new CastsStatefulEnumsModel() : new CastsStatefulEnumsLowerCaseModel();
        $model->$key = $enum;

        $this->assertEquals(
            EnumValue::value($enum, $keepCase),
            $model->toArray()[$key],
        );
    }

    public function testShouldHandleNull()
    {
        $model = new CastsBasicEnumsModel();
        $model->unitEnum = null;

        $this->assertEquals(null, $model->unitEnum);
    }

    public function testShouldHandleObjectInAttribute()
    {
        $model = new CastsBasicEnumsModel();
        $model->setRawAttributes(['unitEnum' => SubsetUnitEnum::ENUM]);

        $this->assertEquals(SubsetUnitEnum::ENUM, $model->unitEnum);
    }

    public function testShouldHandleStringValue()
    {
        $model = new CastsBasicEnumsModel();
        $model->unitEnum = 'enum';

        $this->assertEquals('ENUM', $model->getAttributes()['unitEnum']);

        $this->assertEquals(SubsetUnitEnum::ENUM, $model->unitEnum);
    }

    public function testShouldHandleStringValueLowerCase()
    {
        $model = new CastsBasicEnumsLowerCaseModel();
        $model->unitEnum = 'ENUM';

        $this->assertEquals('enum', $model->getAttributes()['unitEnum']);
    }

    public function testShouldFailIfStringIsNotValid()
    {
        $this->expectException(ValueError::class);
        $model = new CastsStatefulEnumsModel();
        $model->unitEnum = 'NotAnEnum';
    }

    public function testShouldFailIfEnumIsNotValid()
    {
        $this->expectException(ValueError::class);

        $model = new CastsStatefulEnumsModel();
        $model->unitEnum = IntBackedEnum::TEST;
    }

    public function testShouldNotThrowErrorWhenChangingStatelessEnumValue()
    {
        $model = new CastsStatefulEnumsModel();
        $model->unitEnum = 'Enum';
        $model->unitEnum = 'THIRD_ENUM';

        $this->assertEquals(SubsetUnitEnum::THIRD_ENUM, $model->unitEnum);
    }

    public function testShouldJustCastWhenEnumIsNotStatefulValue()
    {
        $model = new CastsStatefulEnumsModel();
        $model->intBackedEnum = 0;
        $model->intBackedEnum = 2;

        $this->assertEquals(IntBackedEnum::TEST_3, $model->intBackedEnum);
    }

    public function testShouldAllowTransition()
    {
        $model = new CastsStatefulEnumsModel();

        $model->stringBackedEnum = StringBackedGetEnum::TEST;
        $model->stringBackedEnum = StringBackedGetEnum::TEST1;

        $this->assertEquals(StringBackedGetEnum::TEST1, $model->stringBackedEnum);
    }

    public function testShouldThrowExceptionWhenTransitionIsNotAllowed()
    {
        $this->expectException(IllegalEnumTransitionException::class);
        $model = new CastsStatefulEnumsModel();

        $model->stringBackedEnum = StringBackedGetEnum::TEST;
        $model->stringBackedEnum = StringBackedGetEnum::TEST_STRING_TO_UPPER;
    }

    public function testShouldThrowExceptionWhenTransitionIsNotAllowedWithHook()
    {
        $model = new class extends Model {
            use CastsStatefulEnumerations;

            protected $casts = [
                'state' => StateElevatorEnum::class
            ];

            public function getTransactionHooks(string $attribute): ?TransitionHook
            {
                return match ($attribute) {
                    'state' => new class extends TransitionHook {
                        protected function allowsOpenClose(): bool
                        {
                            return false;
                        }
                    }
                };
            }
        };

        $model->state = StateElevatorEnum::Open;
        $this->expectException(IllegalEnumTransitionException::class);
        $model->state = StateElevatorEnum::Close;
    }
}
