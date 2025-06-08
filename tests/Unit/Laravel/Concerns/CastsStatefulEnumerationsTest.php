<?php

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

test('should cast correctly from string', function (UnitEnum $enum, string $key, bool $keepCase = true) {
    $model = $keepCase ? new CastsStatefulEnumsModel() : new CastsStatefulEnumsLowerCaseModel();
    $model->setRawAttributes([
        $key => EnumValue::value($enum, $keepCase)
    ]);

    expect($model->$key)->toBe($enum);
})->with([
    [SubsetUnitEnum::ENUM, 'unitEnum'],
    [IntBackedEnum::TEST, 'intBackedEnum'],
    [StringBackedGetEnum::TEST, 'stringBackedEnum'],
    [SubsetUnitEnum::ENUM, 'unitEnum', false],
    [IntBackedEnum::TEST, 'intBackedEnum', false],
    [StringBackedGetEnum::TEST, 'stringBackedEnum', false],
]);

test('should cast correctly to string', function (UnitEnum $enum, string $key, bool $keepCase = true) {
    $model = $keepCase ? new CastsStatefulEnumsModel() : new CastsStatefulEnumsLowerCaseModel();
    $model->$key = $enum;

    $result = $model->toArray()[$key];
    $expected = EnumValue::value($enum, $keepCase);
    expect((string)$result)->toBe((string)$expected);
})->with([
    [SubsetUnitEnum::ENUM, 'unitEnum'],
    [IntBackedEnum::TEST, 'intBackedEnum'],
    [StringBackedGetEnum::TEST, 'stringBackedEnum'],
    [SubsetUnitEnum::ENUM, 'unitEnum', false],
    [IntBackedEnum::TEST, 'intBackedEnum', false],
    [StringBackedGetEnum::TEST, 'stringBackedEnum', false],
]);

test('should handle null', function () {
    $model = new CastsBasicEnumsModel();
    $model->unitEnum = null;

    expect($model->unitEnum)->toBeNull();
});

test('should handle object in attribute', function () {
    $model = new CastsBasicEnumsModel();
    $model->setRawAttributes(['unitEnum' => SubsetUnitEnum::ENUM]);

    expect($model->unitEnum)->toBe(SubsetUnitEnum::ENUM);
});

test('should handle string value', function () {
    $model = new CastsBasicEnumsModel();
    $model->unitEnum = 'enum';

    expect($model->getAttributes()['unitEnum'])->toBe('ENUM');
    expect($model->unitEnum)->toBe(SubsetUnitEnum::ENUM);
});

test('should handle string value lower case', function () {
    $model = new CastsBasicEnumsLowerCaseModel();
    $model->unitEnum = 'ENUM';

    expect($model->getAttributes()['unitEnum'])->toBe('enum');
});

test('should fail if string is not valid', function () {
    $model = new CastsStatefulEnumsModel();
    $model->unitEnum = 'NotAnEnum';
})->throws(ValueError::class);

test('should fail if enum is not valid', function () {
    $model = new CastsStatefulEnumsModel();
    $model->unitEnum = IntBackedEnum::TEST;
})->throws(ValueError::class);

test('should not throw error when changing stateless enum value', function () {
    $model = new CastsStatefulEnumsModel();
    $model->unitEnum = 'Enum';
    $model->unitEnum = 'THIRD_ENUM';

    expect($model->unitEnum)->toBe(SubsetUnitEnum::THIRD_ENUM);
});

test('should just cast when enum is not stateful value', function () {
    $model = new CastsStatefulEnumsModel();
    $model->intBackedEnum = 0;
    $model->intBackedEnum = 2;

    expect($model->intBackedEnum)->toBe(IntBackedEnum::TEST_3);
});

test('should allow transition', function () {
    $model = new CastsStatefulEnumsModel();

    $model->stringBackedEnum = StringBackedGetEnum::TEST;
    $model->stringBackedEnum = StringBackedGetEnum::TEST1;

    expect($model->stringBackedEnum)->toBe(StringBackedGetEnum::TEST1);
});

test('should throw exception when transition is not allowed', function () {
    $model = new CastsStatefulEnumsModel();

    $model->stringBackedEnum = StringBackedGetEnum::TEST;
    $model->stringBackedEnum = StringBackedGetEnum::TEST_STRING_TO_UPPER;
})->throws(IllegalEnumTransitionException::class);

test('should throw exception when transition is not allowed with hook', function () {
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
    $model->state = StateElevatorEnum::Close;
})->throws(IllegalEnumTransitionException::class);
