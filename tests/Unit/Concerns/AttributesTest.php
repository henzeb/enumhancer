<?php

use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\AnotherAttribute;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\AttributesEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\ClassAttributesEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\Description;

test('should get description', function () {
    $description = AttributesEnum::WithAttribute->getAttribute(Description::class);

    expect($description)->toBeInstanceOf(Description::class);
    expect($description->value)->toBe('has description');
});

test('should get another attribute', function () {
    $description = AttributesEnum::WithMixedAttributes->getAttribute(AnotherAttribute::class);

    expect($description)->toBeInstanceOf(AnotherAttribute::class);
});

test('should return null when no description', function () {
    $description = AttributesEnum::WithoutAttribute->getAttribute(Description::class);

    expect($description)->toBeNull();
});

test('should get descriptions', function () {
    $descriptions = AttributesEnum::WithMultipleAttributes->getAttributes(Description::class);

    expect($descriptions)->toHaveCount(2);
    expect($descriptions[0])->toBeInstanceOf(Description::class);
    expect($descriptions[1])->toBeInstanceOf(Description::class);
    expect($descriptions[0]->value)->toBe('has description');
    expect($descriptions[1]->value)->toBe('and another one');
});

test('should get all attributes', function () {
    $descriptions = AttributesEnum::WithMultipleAttributes->getAttributes();

    expect($descriptions)->toHaveCount(2);
    expect($descriptions[0])->toBeInstanceOf(Description::class);
    expect($descriptions[1])->toBeInstanceOf(Description::class);
    expect($descriptions[0]->value)->toBe('has description');
    expect($descriptions[1]->value)->toBe('and another one');
});

test('should get mixed attributes', function () {
    $descriptions = AttributesEnum::WithMixedAttributes->getAttributes();

    expect($descriptions)->toHaveCount(2);
    expect($descriptions[0])->toBeInstanceOf(Description::class);
    expect($descriptions[1])->toBeInstanceOf(AnotherAttribute::class);
    expect($descriptions[0]->value)->toBe('has description');
});

test('get attributes should get empty array', function () {
    $descriptions = AttributesEnum::WithoutAttribute->getAttributes(Description::class);

    expect($descriptions)->toHaveCount(0);
});

test('get attributes should get empty array with non existent attribute', function () {
    $descriptions = AttributesEnum::WithAttribute->getAttributes(AnotherAttribute::class);

    expect($descriptions)->toHaveCount(0);
});

test('get enum attribute returns null', function () {
    expect(AttributesEnum::getEnumAttribute(Description::class))->toBeNull();
});

test('get enum attribute returns empty', function () {
    expect(AttributesEnum::getEnumAttributes(Description::class))->toBeEmpty();
});

test('get enum attribute', function () {
    $description = ClassAttributesEnum::getEnumAttribute(Description::class);

    expect($description)->toBeInstanceOf(Description::class);
    expect($description->value)->toBe('test');
});

test('get enum attributes by name', function () {
    $description = ClassAttributesEnum::getEnumAttributes(Description::class);

    expect($description[0])->toBeInstanceOf(Description::class);
    expect($description[0]->value)->toBe('test');
});

test('get enum attributes without name', function () {
    $description = ClassAttributesEnum::getEnumAttributes();

    expect($description)->toHaveCount(2);
    expect($description[0])->toBeInstanceOf(Description::class);
    expect($description[1])->toBeInstanceOf(AnotherAttribute::class);
    expect($description[0]->value)->toBe('test');
});