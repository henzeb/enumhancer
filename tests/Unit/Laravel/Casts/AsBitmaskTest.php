<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Casts;

use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Laravel\Casts\AsBitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmaskPreferenceEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBitmaskEnumsModel;
use Henzeb\Enumhancer\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use TypeError;


uses(TestCase::class)->in('Unit');

beforeEach(function () {
    $this->attr = 'preferences';

    $this->app['db']->connection()
        ->getSchemaBuilder()
        ->create('casts_bitmask_enums', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger($this->attr)
                ->default(BitmaskPreferenceEnum::allOptionsEnabled())
                ->comment('bitmask preferences');

            $table->timestamps();
        });
});


it('throws exception for invalid enums', function () {
    new AsBitmask('InvalidEnum');

})->throws(InvalidArgumentException::class, 'Enum class [InvalidEnum] does not exist.');


# set
test('`set` method returns correct mask for enum input', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    expect($cast->set($model, $this->attr, BitmaskPreferenceEnum::AutoUpdates, []))
        ->toBe(16)
        ->and($cast->set($model, $this->attr, BitmaskPreferenceEnum::PushNotification, []))
        ->toBe(2)
        ->and($cast->set($model, $this->attr, BitmaskPreferenceEnum::DarkMode, []))
        ->toBe(8)
        ->and($cast->set($model, $this->attr, BitmaskPreferenceEnum::LogActivity, []))
        ->toBe(1);
});

test('`set` method throws exception for invalid enum value type', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    $cast->set($model, $this->attr, BitmasksIncorrectIntEnum::Read, []);

})->throws(TypeError::class, 'This method can only be used with an enum');

test('`set` method returns correct mask for bitmask input', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    # test 1
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::AutoUpdates
    );

    $result = $cast->set($model, $this->attr, $value, []);
    expect($result)->toBe(16);


    # test 2
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::AutoUpdates,
        BitmaskPreferenceEnum::PushNotification
    );

    $result = $cast->set($model, $this->attr, $value, []);
    expect($result)->toBe(18);


    # test 3
    $value = BitmaskPreferenceEnum::mask();
    $result = $cast->set($model, $this->attr, $value, []);
    expect($result)->toBe(0);
});

test('`set` method throws exception for invalid bitmask value type', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    $value = BitmasksIncorrectIntEnum::mask(
        BitmasksIncorrectIntEnum::Read,
        BitmasksIncorrectIntEnum::Execute,
    );

    $cast->set($model, $this->attr, $value, []);

})->throws(TypeError::class, 'Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum::Execute is not a valid bit value');

test('`set` method returns correct mask for string input', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);


    # test 1
    $result = $cast->set($model, $this->attr, 'AutoUpdates', []);
    expect($result)->toBe(16);


    # test 2
    $result = $cast->set($model, $this->attr, 'PushNotification', []);
    expect($result)->toBe(2);


    # test 3
    $result = $cast->set($model, $this->attr, 'DarkMode', []);
    expect($result)->toBe(8);


    # test 4
    $result = $cast->set($model, $this->attr, 'LogActivity', []);
    expect($result)->toBe(1);


    # test 5
    $result = $cast->set($model, $this->attr, 'LogActivity,DarkMode', []);
    expect($result)->toBe(9);


    # test 6
    $result = $cast->set($model, $this->attr, 'LogActivity, PushNotification ,DarkMode', []);
    expect($result)->toBe(11);


    # test 7
    $result = $cast->set($model, $this->attr, '', []);
    expect($result)->toBe(0);
});

test('`set` method throws exception for invalid string value type', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    $cast->set($model, $this->attr, 'RANDOM_CASE', []);

})->throws(TypeError::class, 'This method can only be used with an enum');

test('`set` method returns correct mask for array input', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);


    # test 1
    $result = $cast->set($model, $this->attr, [BitmaskPreferenceEnum::DarkMode, BitmaskPreferenceEnum::LogActivity], []);
    expect($result)->toBe(9);


    # test 2
    $result = $cast->set($model, $this->attr, [BitmaskPreferenceEnum::TwoFactorAuth], []);
    expect($result)->toBe(4);


    # test 3
    $result = $cast->set($model, $this->attr, [BitmaskPreferenceEnum::TwoFactorAuth, BitmaskPreferenceEnum::DataExport, BitmaskPreferenceEnum::PushNotification], []);
    expect($result)->toBe(38);


    # test 4
    $result = $cast->set($model, $this->attr, [], []);
    expect($result)->toBe(0);


    # test 5
    $result = $cast->set($model, $this->attr, [BitmaskPreferenceEnum::TwoFactorAuth, 'AutoUpdates', BitmaskPreferenceEnum::PushNotification], []);
    expect($result)->toBe(22);


    # test 6
    $result = $cast->set($model, $this->attr, ['DataExport', 'AutoUpdates'], []);
    expect($result)->toBe(48);
});

test('`set` method throws exception for invalid array value type', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    $cast->set($model, $this->attr, [BitmaskPreferenceEnum::DarkMode, BitmasksIncorrectIntEnum::Read], []);

})->throws(TypeError::class);

test('`set` method throws exception for invalid value types', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    $cast->set($model, $this->attr, 3, []);

})->throws(InvalidArgumentException::class);


# get
test('`get` method returns bitmask for valid value', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);


    # test 1
    $result = $cast->get($model, $this->attr, 16, []);
    expect($result->value())
        ->toBe(16)
        ->and($result->has(BitmaskPreferenceEnum::AutoUpdates))
        ->toBeTrue();


    # test 2
    $result = $cast->get($model, $this->attr, 17, []);
    expect($result->value())
        ->toBe(17)
        ->and($result->has(BitmaskPreferenceEnum::LogActivity))
        ->toBeTrue()
        ->and($result->has(BitmaskPreferenceEnum::AutoUpdates))
        ->toBeTrue();

    # test 3
    $result = $cast->get($model, $this->attr, '3', []);
    expect($result->value())
        ->toBe(3)
        ->and($result->has(BitmaskPreferenceEnum::LogActivity))
        ->toBeTrue()
        ->and($result->has(BitmaskPreferenceEnum::PushNotification))
        ->toBeTrue();


    # test 4
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::LogActivity,
        BitmaskPreferenceEnum::DarkMode,
    );

    $result = $cast->get($model, $this->attr, $value, []);
    expect($result->value())
        ->toBe(9)
        ->and($result)
        ->toBe($value)
        ->and($result->has(BitmaskPreferenceEnum::LogActivity))
        ->toBeTrue()
        ->and($result->has(BitmaskPreferenceEnum::DarkMode))
        ->toBeTrue();
});


# model
test('returns all items enabled', function () {
    DB::table('casts_bitmask_enums')->insert([
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $record = CastsBitmaskEnumsModel::query()->first();

    expect($record->preferences)
        ->toBeInstanceOf(Bitmask::class)
        ->and($record->preferences->value())
        ->toBe(63)
        ->and($record->preferences->has(BitmaskPreferenceEnum::LogActivity))
        ->toBeTrue()
        ->and($record->preferences->has(BitmaskPreferenceEnum::PushNotification))
        ->toBeTrue()
        ->and($record->preferences->has(BitmaskPreferenceEnum::PushNotification))
        ->toBeTrue()
        ->and($record->preferences->has(BitmaskPreferenceEnum::DarkMode))
        ->toBeTrue()
        ->and($record->preferences->has(BitmaskPreferenceEnum::AutoUpdates))
        ->toBeTrue()
        ->and($record->preferences->has(BitmaskPreferenceEnum::DataExport))
        ->toBeTrue();
});

test('stores correct mask to database', function (mixed $preferences, int $value, string $serialized, array $has) {
    $model = new CastsBitmaskEnumsModel;
    $model->preferences = $preferences;
    $model->save();


    # test 1
    expect($model->preferences->value())->toBe($value);


    foreach ($has as $item) {
        expect($model->preferences->has($item))->toBeTrue();
    }

    $array = $model->toArray();
    expect($array['preferences'])->toBe($serialized);


    # test 2
    $model = CastsBitmaskEnumsModel::find($model->id);

    expect($model->preferences->value())->toBe($value);

    foreach ($has as $item) {
        expect($model->preferences->has($item))->toBeTrue();
    }

    $array = $model->toArray();
    expect($array['preferences'])->toBe($serialized);

})->with([
    [
        'preferences' => BitmaskPreferenceEnum::AutoUpdates,
        'value'       => 16,
        'serialized'  => 'AutoUpdates',
        'has'         => [
            BitmaskPreferenceEnum::AutoUpdates
        ]
    ],
    [
        'preferences' => 'DarkMode',
        'value'       => 8,
        'serialized'  => 'DarkMode',
        'has'         => [
            BitmaskPreferenceEnum::DarkMode
        ]
    ],
    [
        'preferences' => 'DataExport,TwoFactorAuth,PushNotification',
        'value'       => 38,
        'serialized'  => 'PushNotification,TwoFactorAuth,DataExport',
        'has'         => [
            BitmaskPreferenceEnum::DataExport,
            BitmaskPreferenceEnum::TwoFactorAuth,
            BitmaskPreferenceEnum::PushNotification,
        ]
    ],
    [
        'preferences' => ' DataExport, TwoFactorAuth ,DarkMode ',
        'value'       => 44,
        'serialized'  => 'TwoFactorAuth,DarkMode,DataExport',
        'has'         => [
            BitmaskPreferenceEnum::DataExport,
            BitmaskPreferenceEnum::TwoFactorAuth,
            BitmaskPreferenceEnum::DarkMode,
        ]
    ],
    [
        'preferences' => '',
        'value'       => 0,
        'serialized'  => '',
        'has'         => []
    ],
    [
        'preferences' => [
            BitmaskPreferenceEnum::DarkMode,
            BitmaskPreferenceEnum::LogActivity,
        ],
        'value'       => 9,
        'serialized'  => 'LogActivity,DarkMode',
        'has'         => [
            BitmaskPreferenceEnum::DarkMode,
            BitmaskPreferenceEnum::LogActivity,
        ]
    ],
    [
        'preferences' => BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::TwoFactorAuth,
            BitmaskPreferenceEnum::LogActivity,
        ),
        'value'       => 5,
        'serialized'  => 'LogActivity,TwoFactorAuth',
        'has'         => [
            BitmaskPreferenceEnum::TwoFactorAuth,
            BitmaskPreferenceEnum::LogActivity,
        ]
    ],
    [
        'preferences' => BitmaskPreferenceEnum::mask(),
        'value'       => 0,
        'serialized'  => '',
        'has'         => []
    ],
    [
        'preferences' => [],
        'value'       => 0,
        'serialized'  => '',
        'has'         => []
    ],
]);

test('bitmask operations', function () {
    $model = new CastsBitmaskEnumsModel;
    $model->preferences = [BitmaskPreferenceEnum::DarkMode, BitmaskPreferenceEnum::AutoUpdates];
    $model->save();


    # test 1
    expect($model->preferences->has(BitmaskPreferenceEnum::DarkMode))
        ->toBeTrue()
        ->and($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates))
        ->toBeTrue();


    # test 2
    $model->preferences = $model->preferences->set(BitmaskPreferenceEnum::LogActivity);
    $model->preferences = $model->preferences->unset(BitmaskPreferenceEnum::AutoUpdates);
    $model->save();

    expect($model->preferences->has(BitmaskPreferenceEnum::LogActivity))
        ->toBeTrue()
        ->and($model->preferences->has(BitmaskPreferenceEnum::DarkMode))
        ->toBeTrue()
        ->and($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates))
        ->toBeFalse();



    # test 3
    $model->preferences = $model->preferences->toggle(BitmaskPreferenceEnum::AutoUpdates);
    $model->preferences = $model->preferences->toggle(BitmaskPreferenceEnum::LogActivity);
    $model->save();

    expect($model->preferences->has(BitmaskPreferenceEnum::LogActivity))
        ->toBeFalse()
        ->and($model->preferences->has(BitmaskPreferenceEnum::DarkMode))
        ->toBeTrue()
        ->and($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates))
        ->toBeTrue();



    # test 4
    $model = CastsBitmaskEnumsModel::find($model->id);

    expect($model->preferences->has(BitmaskPreferenceEnum::DarkMode))
        ->toBeTrue()
        ->and($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates))
        ->toBeTrue()
        ->and($model->preferences->has(BitmaskPreferenceEnum::LogActivity))
        ->toBeFalse()
        ->and($model->preferences->value())
        ->toBe(24);
});



# serialize
test('`serialize` method returns empty string for non bitmask values', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);

    $result = $cast->serialize($model, $this->attr, '', []);
    expect($result)->toBe('');
});

test('`serialize` method returns correct string for bitmask values', function () {
    $model = new CastsBitmaskEnumsModel;
    $enum = BitmaskPreferenceEnum::class;
    $cast = new AsBitmask($enum);



    # test 1
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::LogActivity,
        BitmaskPreferenceEnum::DarkMode,
    );

    $result = $cast->serialize($model, $this->attr, $value, []);
    expect($result)->toBe('LogActivity,DarkMode');


    # test 2
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::AutoUpdates,
    );

    $result = $cast->serialize($model, $this->attr, $value, []);
    expect($result)->toBe('AutoUpdates');


    # test 3
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::LogActivity,
        BitmaskPreferenceEnum::DataExport,
        BitmaskPreferenceEnum::DarkMode,
    );

    $result = $cast->serialize($model, 'preferences', $value, []);
    expect($result)->toBe('LogActivity,DarkMode,DataExport');
});
