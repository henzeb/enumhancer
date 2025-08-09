<?php

use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBasicEnumsLowerCaseModel;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBasicEnumsModel;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBasicEnumsNoPropertyModel;
use Henzeb\Enumhancer\Tests\Fixtures\StringBackedGetEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SubsetUnitEnum;


test('should cast correctly from string', function (\UnitEnum $enum, string $key, bool $keepCase = true) {
    $model = $keepCase ? new CastsBasicEnumsModel() : new CastsBasicEnumsLowerCaseModel();
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

test('should cast correctly to string', function (\UnitEnum $enum, string $key, bool $keepCase = true) {
    $model = $keepCase ? new CastsBasicEnumsModel() : new CastsBasicEnumsLowerCaseModel();
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
    $model = new CastsBasicEnumsModel();
    $model->unitEnum = 'NotAnEnum';
})->throws(ValueError::class);

test('should fail if enum is not valid', function () {
    $model = new CastsBasicEnumsModel();
    $model->unitEnum = IntBackedEnum::TEST;
})->throws(ValueError::class);

test('should use default keepEnumCase when property does not exist', function () {
    $model = new CastsBasicEnumsNoPropertyModel();
    $model->unitEnum = SubsetUnitEnum::ENUM;

    // This should use keepEnumCase = true (default) since the property doesn't exist
    expect($model->getAttributes()['unitEnum'])->toBe('ENUM');
});

test('should handle unit enum in toArray when shouldUseBasicEnumWorkaround returns true', function () {
    $model = new CastsBasicEnumsNoPropertyModel();
    $model->unitEnum = SubsetUnitEnum::ENUM;

    // This will trigger shouldUseBasicEnumWorkaround and test line 34
    $array = $model->toArray();
    expect((string)$array['unitEnum'])->toBe('ENUM');
});

test('should return non-enum value in getStorableEnumValue', function () {
    $model = new CastsBasicEnumsModel();

    // Use reflection to test the protected method
    $reflection = new ReflectionClass($model);
    $method = $reflection->getMethod('getStorableEnumValue');
    $method->setAccessible(true);

    // Test with a non-UnitEnum value - this should hit line 79
    $result = $method->invoke($model, 'some_string', 'some_string');
    expect($result)->toBe('some_string');
});
