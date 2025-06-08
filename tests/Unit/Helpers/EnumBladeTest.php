<?php

use Henzeb\Enumhancer\Exceptions\NotAnEnumException;
use Henzeb\Enumhancer\Helpers\EnumBlade;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedEnum;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase;
use function Henzeb\Enumhancer\Functions\backing;
use function Henzeb\Enumhancer\Functions\name;
use function Henzeb\Enumhancer\Functions\value;

uses(TestCase::class, InteractsWithViews::class);

test('should render value', function ($enum, $keepValueCase = true) {
    $method = $keepValueCase ? 'register' : 'registerLowercase';
    EnumBlade::$method($enum::class);

    expect(
        (string)$this->blade('{{ $data }}',
            ['data' => $enum], true
        )
    )->toBe((string)value($enum, $keepValueCase));

    expect(
        (string)$this->blade('{{ $data }}',
            ['data' => backing($enum, $keepValueCase)], true
        )
    )->toBe(backing($enum, $keepValueCase)->value);

    expect(
        (string)$this->blade('{{ $data->value }}',
            ['data' => backing($enum, $keepValueCase)], true
        )
    )->toBe(backing($enum, $keepValueCase)->value);

    expect(
        (string)$this->blade('{{ $data->name }}',
            ['data' => $enum], true
        )
    )->toBe(name($enum));
})->with([
    'int-backed' => [IntBackedEnum::TEST],
    'string-backed' => [EnhancedBackedEnum::ENUM],
    'unit' => [EnhancedUnitEnum::ENUM],

    'int-backed-lower' => [IntBackedEnum::TEST, false],
    'string-backed-lower' => [EnhancedBackedEnum::ENUM, false],
    'unit-lower' => [EnhancedUnitEnum::ENUM, false],
]);

test('should fail adding non enum lowercase', function () {
    EnumBlade::registerLowercase(stdClass::class);
})->throws(NotAnEnumException::class);

test('should fail adding non enum', function () {
    EnumBlade::register(stdClass::class);
})->throws(NotAnEnumException::class);
