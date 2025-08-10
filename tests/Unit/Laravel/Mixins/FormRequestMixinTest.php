<?php

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Illuminate\Foundation\Http\FormRequest;


test('as enum', function () {
    $request = new FormRequest(
        [
            'myEnum' => 'open',
            'myInvalidEnum' => 'invalid',
            'myNullEnum' => null,
        ]
    );

    expect($request->asEnum('myEnum', SimpleEnum::class))->toBe(SimpleEnum::Open);
    expect($request->asEnum('myInvalidEnum', SimpleEnum::class))->toBeNull();
    expect($request->asEnum('myDoesNotExistEnum', SimpleEnum::class))->toBeNull();
    expect($request->asEnum('myNullEnum', SimpleEnum::class))->toBeNull();
});

test('as enum default', function () {
    $request = new FormRequest([
        'myEnum' => 'default',
        'myInvalidEnum' => 'invalid',
        'NullEnum' => null,
    ]);

    expect($request->asEnum('myEnum', DefaultsEnum::class))->toBe(DefaultsEnum::default());
    expect($request->asEnum('myInvalidEnum', DefaultsEnum::class))->toBe(DefaultsEnum::default());
    expect($request->asEnum('myDoesNotExistEnum', DefaultsEnum::class))->toBe(DefaultsEnum::default());
    expect($request->asEnum('nullEnum', DefaultsEnum::class))->toBe(DefaultsEnum::default());
});

test('as enum with mapper', function () {
    $request = new FormRequest([
        'myEnum' => 'opened',
    ]);

    expect($request->asEnum('myEnum', SimpleEnum::class, ['opened' => 'open']))->toBe(SimpleEnum::Open);

    expect($request->asEnum('myEnum', SimpleEnum::class, ['opened' => 'opening'], ['opening' => 'open']))->toBe(SimpleEnum::Open);

    $mapper = new class extends Mapper
    {
        protected function mappable(): array
        {
            return [
                'opened'=>'opening'
            ];
        }
    };

    expect($request->asEnum('myEnum', SimpleEnum::class, $mapper, ['opening'=>'open']))->toBe(SimpleEnum::Open);
    expect($request->asEnum('myEnum', SimpleEnum::class, $mapper::class, ['opening'=>'open']))->toBe(SimpleEnum::Open);
});
