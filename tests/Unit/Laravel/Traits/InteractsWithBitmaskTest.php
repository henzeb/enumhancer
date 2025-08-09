<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Traits;

use Closure;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmaskPreferenceEnum;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBitmaskEnumsModel;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase;


class InteractsWithBitmaskTest extends TestCase
{
    private CastsBitmaskEnumsModel $record1;
    private CastsBitmaskEnumsModel $record2;
    private CastsBitmaskEnumsModel $record3;
    private CastsBitmaskEnumsModel $record4;

    protected function getPackageProviders($app): array
    {
        return [
            EnumhancerServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUpDatabaseRequirements(Closure $callback): void
    {
        $this->app['db']->connection()
            ->getSchemaBuilder()
            ->create('casts_bitmask_enums', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('preferences')
                    ->default(BitmaskPreferenceEnum::allOptionsEnabled())
                    ->comment('bitmask preferences');

                $table->timestamps();
            });


        $this->record1 = CastsBitmaskEnumsModel::query()->create([
            'preferences' => [
                BitmaskPreferenceEnum::AutoUpdates,
                BitmaskPreferenceEnum::DarkMode,
            ]
        ]);

        $this->record2 = CastsBitmaskEnumsModel::query()->create([
            'preferences' => BitmaskPreferenceEnum::DataExport
        ]);

        $this->record3 = CastsBitmaskEnumsModel::query()->create([
            'preferences' => 'TwoFactorAuth,DarkMode,PushNotification'
        ]);

        $this->record4 = CastsBitmaskEnumsModel::query()->create([
            'preferences' => []
        ]);
    }


    # where
    public function testWhereBitmaskAppliesCorrectConditionForIntegerValue(): void
    {
        # test 1
        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 8)->get();
        $this->assertCount(2, $results);
        $this->assertEquals([$this->record1->id, $this->record3->id], $results->pluck('id')->toArray());


        # test 2
        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 32)->get();
        $this->assertCount(1, $results);
        $this->assertEquals($this->record2->id, $results->first()->id);


        # test 3
        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 24)->get();
        $this->assertCount(1, $results);
        $this->assertEquals($this->record1->id, $results->first()->id);


        # test 4
        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 1)->get();
        $this->assertCount(0, $results);


        # test 4
        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', 0)->get();
        $this->assertCount(1, $results);
        $this->assertEquals($this->record4->id, $results->first()->id);
    }

    public function testWhereBitmaskAppliesCorrectConditionForBitmaskValue(): void
    {
        # test 1
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::DarkMode
        );

        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
        $this->assertCount(2, $results);
        $this->assertEquals([$this->record1->id, $this->record3->id], $results->pluck('id')->toArray());


        # test 2
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::PushNotification
        );

        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
        $this->assertCount(1, $results);
        $this->assertEquals($this->record3->id, $results->first()->id);


        # test 3
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::PushNotification,
            BitmaskPreferenceEnum::DarkMode,
            BitmaskPreferenceEnum::TwoFactorAuth,
        );

        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
        $this->assertCount(1, $results);
        $this->assertEquals($this->record3->id, $results->first()->id);


        # test 4
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::PushNotification,
            BitmaskPreferenceEnum::DarkMode,
            BitmaskPreferenceEnum::AutoUpdates,
        );

        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
        $this->assertCount(0, $results);


        # test 5
        $value = BitmaskPreferenceEnum::mask();
        $results = CastsBitmaskEnumsModel::whereBitmask('preferences', $value)->get();
        $this->assertCount(1, $results);
        $this->assertEquals($this->record4->id, $results->first()->id);
    }


    # or-where
    public function testOrWhereBitmaskAppliesCorrectConditionForIntegerValue(): void
    {
        # test 1
        $results = CastsBitmaskEnumsModel::query()
            ->whereBitmask('preferences', 1)
            ->orWhereBitmask('preferences', 8)
            ->get();

        $this->assertCount(2, $results);
        $this->assertEquals([$this->record1->id, $this->record3->id], $results->pluck('id')->toArray());


        # test 2
        $results = CastsBitmaskEnumsModel::query()
            ->whereBitmask('preferences', 3)
            ->orWhereBitmask('preferences', 32)
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals($this->record2->id, $results->first()->id);


        # test 3
        $results = CastsBitmaskEnumsModel::query()
            ->whereBitmask('preferences', 1)
            ->orWhereBitmask('preferences', 24)
            ->orWhereBitmask('preferences', 32)
            ->get();

        $this->assertCount(2, $results);
        $this->assertEquals([$this->record1->id, $this->record2->id], $results->pluck('id')->toArray());


        # test 4
        $results = CastsBitmaskEnumsModel::query()
            ->whereBitmask('preferences', 1)
            ->orWhereBitmask('preferences', 3)
            ->get();

        $this->assertCount(0, $results);


        # test 5
        $results = CastsBitmaskEnumsModel::query()
            ->whereBitmask('preferences', 1)
            ->orWhereBitmask('preferences', 0)
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals($this->record4->id, $results->first()->id);


        # test 6
        $results = CastsBitmaskEnumsModel::query()
            ->whereBitmask('preferences', 1)
            ->orWhereBitmask('preferences', 24)
            ->orWhereBitmask('preferences', 32)
            ->orWhereBitmask('preferences', 8)
            ->get();

        $this->assertCount(3, $results);
        $this->assertEquals([$this->record1->id, $this->record2->id, $this->record3->id], $results->pluck('id')->toArray());

    }

    public function testOrWhereBitmaskAppliesCorrectConditionForBitmaskValue(): void
    {
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

        $this->assertCount(2, $results);
        $this->assertEquals([$this->record1->id, $this->record3->id], $results->pluck('id')->toArray());


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

        $this->assertCount(1, $results);
        $this->assertEquals($this->record2->id, $results->first()->id);


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

        $this->assertCount(2, $results);
        $this->assertEquals([$this->record1->id, $this->record2->id], $results->pluck('id')->toArray());


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

        $this->assertCount(0, $results);


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

        $this->assertCount(1, $results);
        $this->assertEquals($this->record4->id, $results->first()->id);


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

        $this->assertCount(3, $results);
        $this->assertEquals([$this->record1->id, $this->record2->id, $this->record3->id], $results->pluck('id')->toArray());
    }
}
