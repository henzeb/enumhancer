<?php

use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Labels\LabelByKeyEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Labels\LabelNoLabelsEnum;

test('should get name when no labels specified at all', function () {
    expect(LabelNoLabelsEnum::NO_LABEL->label())->toBe('NO_LABEL');
    expect(LabelNoLabelsEnum::nolabel->label())->toBe('nolabel');
    expect(LabelNoLabelsEnum::NoLabel->label())->toBe('NoLabel');
});

test('should get label by name', function () {
    expect(EnhancedBackedEnum::ENUM->label())->toBe('My label');
});

test('should get value when label does not exist', function () {
    expect(EnhancedBackedEnum::ANOTHER_ENUM->label())->toBe('another enum');
});

test('should get label by key', function () {
    expect(LabelByKeyEnum::LabelByKey->label())->toBe('label 1');
    expect(LabelByKeyEnum::LabelByKey2->label())->toBe('label 2');
});
