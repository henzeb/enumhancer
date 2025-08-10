<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Traits;

use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmaskPreferenceEnum;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBitmaskEnumsModel;
use Illuminate\Database\Schema\Blueprint;


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


    $this->record1 = CastsBitmaskEnumsModel::query()->create([
        $this->attr => [
            BitmaskPreferenceEnum::AutoUpdates,
            BitmaskPreferenceEnum::DarkMode,
        ]
    ]);

    $this->record2 = CastsBitmaskEnumsModel::query()->create([
        $this->attr => BitmaskPreferenceEnum::DataExport
    ]);

    $this->record3 = CastsBitmaskEnumsModel::query()->create([
        $this->attr => 'TwoFactorAuth,DarkMode,PushNotification'
    ]);

    $this->record4 = CastsBitmaskEnumsModel::query()->create([
        $this->attr => []
    ]);
});


# where
test('whereBitmask applies correct condition for integer value', function () {
    # test 1
    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 8)->get();
    $this->assertEquals([$this->record1->id, $this->record3->id], $results->pluck('id')->toArray());
    expect($results)
        ->toHaveCount(2)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record3->id
        ]);


    # test 2
    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 32)->get();
    $this->assertCount(1, $results);
    $this->assertEquals($this->record2->id, $results->first()->id);
    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record2->id);


    # test 3
    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 24)->get();
    $this->assertCount(1, $results);
    $this->assertEquals($this->record1->id, $results->first()->id);
    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record1->id);


    # test 4
    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 1)->get();
    expect($results)->toHaveCount(0);


    # test 5
    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 0)->get();
    $this->assertCount(1, $results);
    $this->assertEquals($this->record4->id, $results->first()->id);
    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record4->id);
});

test('whereBitmask applies correct condition for bitmask value', function () {
    # test 1
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::DarkMode
    );

    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
    $this->assertCount(2, $results);
    $this->assertEquals([$this->record1->id, $this->record3->id], $results->pluck('id')->toArray());

    expect($results)
        ->toHaveCount(2)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record3->id
        ]);


    # test 2
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::PushNotification
    );

    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
    $this->assertCount(1, $results);
    $this->assertEquals($this->record3->id, $results->first()->id);
    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record3->id);


    # test 3
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::PushNotification,
        BitmaskPreferenceEnum::DarkMode,
        BitmaskPreferenceEnum::TwoFactorAuth,
    );

    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
    $this->assertCount(1, $results);
    $this->assertEquals($this->record3->id, $results->first()->id);
    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record3->id);

    # test 4
    $value = BitmaskPreferenceEnum::mask(
        BitmaskPreferenceEnum::PushNotification,
        BitmaskPreferenceEnum::DarkMode,
        BitmaskPreferenceEnum::AutoUpdates,
    );

    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
    expect($results)->toHaveCount(0);


    # test 5
    $value = BitmaskPreferenceEnum::mask();
    $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record4->id);
});


# or-where
test('orWhereBitmask applies correct condition for integer value', function () {
    # test 1
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask('preferences', 1)
        ->orWhereBitmask('preferences', 8)
        ->get();

    expect($results)
        ->toHaveCount(2)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record3->id
        ]);


    # test 2
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask('preferences', 3)
        ->orWhereBitmask('preferences', 32)
        ->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record2->id);


    # test 3
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask('preferences', 1)
        ->orWhereBitmask('preferences', 24)
        ->orWhereBitmask('preferences', 32)
        ->get();

    $this->assertCount(2, $results);
    $this->assertEquals([$this->record1->id, $this->record2->id], $results->pluck('id')->toArray());
    expect($results)
        ->toHaveCount(2)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record2->id
        ]);


    # test 4
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask('preferences', 1)
        ->orWhereBitmask('preferences', 3)
        ->get();

    expect($results)->toHaveCount(0);


    # test 5
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask('preferences', 1)
        ->orWhereBitmask('preferences', 0)
        ->get();

    $this->assertCount(1, $results);
    $this->assertEquals($this->record4->id, $results->first()->id);
    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record4->id);


    # test 6
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask('preferences', 1)
        ->orWhereBitmask('preferences', 24)
        ->orWhereBitmask('preferences', 32)
        ->orWhereBitmask('preferences', 8)
        ->get();

    expect($results)
        ->toHaveCount(3)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record2->id, $this->record3->id
        ]);

});

test('orWhereBitmask applies correct condition for bitmask value', function () {
    # test 1
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::LogActivity
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::DarkMode
            )
        )
        ->get();

    expect($results)
        ->toHaveCount(2)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record3->id
        ]);


    # test 2
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::LogActivity,
                BitmaskPreferenceEnum::PushNotification,
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::DataExport
            )
        )
        ->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record2->id);


    # test 3
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::LogActivity,
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::AutoUpdates,
                BitmaskPreferenceEnum::DarkMode,
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::DataExport
            )
        )
        ->get();

    expect($results)
        ->toHaveCount(2)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record2->id
        ]);


    # test 4
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::LogActivity,
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::LogActivity,
                BitmaskPreferenceEnum::PushNotification,
            )
        )
        ->get();

    expect($results)->toHaveCount(0);


    # test 5
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::LogActivity,
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask()
        )
        ->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe($this->record4->id);


    # test 6
    $results = CastsBitmaskEnumsModel::query()
        ->whereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::LogActivity,
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::DarkMode,
                BitmaskPreferenceEnum::AutoUpdates,
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::DataExport
            )
        )
        ->orWhereBitmask(
            column: 'preferences',
            value: BitmaskPreferenceEnum::mask(
                BitmaskPreferenceEnum::DarkMode
            )
        )
        ->get();

    expect($results)
        ->toHaveCount(3)
        ->and($results->pluck('id')->toArray())
        ->toBe([
            $this->record1->id, $this->record2->id, $this->record3->id
        ]);
});
