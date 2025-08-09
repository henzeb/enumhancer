<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Casts;

use Closure;
use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Henzeb\Enumhancer\Laravel\Casts\AsBitmask;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmaskPreferenceEnum;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIncorrectIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\Models\CastsBitmaskEnumsModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TypeError;


class AsBitmaskTest extends TestCase
{
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
    }


    # init
    public function testEnumClassExistsThrowsExceptionForInvalidEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Enum class [InvalidEnum] does not exist.');

        new AsBitmask('InvalidEnum');
    }


    # set
    public function testSetReturnsCorrectMaskForEnumInput()
    {
        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $result = $cast->set($model, 'preferences', BitmaskPreferenceEnum::AutoUpdates, []);
        $this->assertEquals(16, $result);

        $result = $cast->set($model, 'preferences', BitmaskPreferenceEnum::PushNotification, []);
        $this->assertEquals(2, $result);

        $result = $cast->set($model, 'preferences', BitmaskPreferenceEnum::DarkMode, []);
        $this->assertEquals(8, $result);

        $result = $cast->set($model, 'preferences', BitmaskPreferenceEnum::LogActivity, []);
        $this->assertEquals(1, $result);
    }

    public function testSetThrowsExceptionForInvalidEnumValueType()
    {
        $this->expectException(TypeError::class);

        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $cast->set($model, 'preferences', BitmasksIncorrectIntEnum::Read, []);
    }

    public function testSetReturnsCorrectMaskForBitmaskInput()
    {
        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);


        # test 1
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::AutoUpdates
        );

        $result = $cast->set($model, 'preferences', $value, []);
        $this->assertEquals(16, $result);


        # test 2
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::AutoUpdates,
            BitmaskPreferenceEnum::PushNotification
        );

        $result = $cast->set($model, 'preferences', $value, []);
        $this->assertEquals(18, $result);


        # test 3
        $value = BitmaskPreferenceEnum::mask();
        $result = $cast->set($model, 'preferences', $value, []);
        $this->assertEquals(0, $result);
    }

    public function testSetThrowsExceptionForInvalidBitmaskValueType()
    {
        $this->expectException(TypeError::class);

        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);


        $value = BitmasksIncorrectIntEnum::mask(
            BitmasksIncorrectIntEnum::Read,
            BitmasksIncorrectIntEnum::Execute,
        );

        $cast->set($model, 'preferences', $value, []);
    }

    public function testSetReturnsCorrectMaskForStringInput()
    {
        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $result = $cast->set($model, 'preferences', 'AutoUpdates', []);
        $this->assertEquals(16, $result);

        $result = $cast->set($model, 'preferences', 'PushNotification', []);
        $this->assertEquals(2, $result);

        $result = $cast->set($model, 'preferences', 'DarkMode', []);
        $this->assertEquals(8, $result);

        $result = $cast->set($model, 'preferences', 'LogActivity', []);
        $this->assertEquals(1, $result);

        $result = $cast->set($model, 'preferences', 'LogActivity,DarkMode', []);
        $this->assertEquals(9, $result);

        $result = $cast->set($model, 'preferences', 'LogActivity, PushNotification ,DarkMode', []);
        $this->assertEquals(11, $result);

        $result = $cast->set($model, 'preferences', '', []);
        $this->assertEquals(0, $result);
    }

    public function testSetThrowsExceptionForInvalidStringValueType()
    {
        $this->expectException(TypeError::class);

        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $cast->set($model, 'preferences', 'RANDOM_CASE', []);
    }

    public function testSetReturnsCorrectMaskForArrayInput()
    {
        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $result = $cast->set($model, 'preferences', [BitmaskPreferenceEnum::DarkMode, BitmaskPreferenceEnum::LogActivity], []);
        $this->assertEquals(9, $result);

        $result = $cast->set($model, 'preferences', [BitmaskPreferenceEnum::TwoFactorAuth], []);
        $this->assertEquals(4, $result);

        $result = $cast->set($model, 'preferences', [BitmaskPreferenceEnum::TwoFactorAuth, BitmaskPreferenceEnum::DataExport, BitmaskPreferenceEnum::PushNotification], []);
        $this->assertEquals(38, $result);

        $result = $cast->set($model, 'preferences', [], []);
        $this->assertEquals(0, $result);

        $result = $cast->set($model, 'preferences', [BitmaskPreferenceEnum::TwoFactorAuth, 'AutoUpdates', BitmaskPreferenceEnum::PushNotification], []);
        $this->assertEquals(22, $result);

        $result = $cast->set($model, 'preferences', ['DataExport', 'AutoUpdates'], []);
        $this->assertEquals(48, $result);
    }

    public function testSetThrowsExceptionForInvalidArrayValueType()
    {
        $this->expectException(TypeError::class);

        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $cast->set($model, 'preferences', [BitmaskPreferenceEnum::DarkMode, BitmasksIncorrectIntEnum::Read], []);
    }

    public function testSetThrowsExceptionForInvalidValueTypes()
    {
        $this->expectException(InvalidArgumentException::class);

        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $cast->set($model, 'preferences', 3, []);
    }


    # model
    public function testReturnsAllItemsEnabled(): void
    {
        DB::table('casts_bitmask_enums')->insert([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $record = CastsBitmaskEnumsModel::query()->first();


        $this->assertInstanceOf(Bitmask::class, $record->preferences);

        $this->assertEquals(63, $record->preferences->value());
        $this->assertTrue($record->preferences->has(BitmaskPreferenceEnum::LogActivity));
        $this->assertTrue($record->preferences->has(BitmaskPreferenceEnum::PushNotification));
        $this->assertTrue($record->preferences->has(BitmaskPreferenceEnum::PushNotification));
        $this->assertTrue($record->preferences->has(BitmaskPreferenceEnum::DarkMode));
        $this->assertTrue($record->preferences->has(BitmaskPreferenceEnum::AutoUpdates));
        $this->assertTrue($record->preferences->has(BitmaskPreferenceEnum::DataExport));
    }

    #[DataProvider('modelTestDataset')]
    public function testStoresCorrectMaskToDatabase($dataset): void
    {
        $model = new CastsBitmaskEnumsModel;
        $model->preferences = $dataset['preferences'];
        $model->save();

        # test 1
        $this->assertEquals($dataset['value'], $model->preferences->value());

        foreach ($dataset['has'] as $has) {
            $this->assertTrue($model->preferences->has($has));
        }

        $array = $model->toArray();
        $this->assertEquals($dataset['serialized'], $array['preferences']);



        # test 2
        $model = CastsBitmaskEnumsModel::find($model->id);

        $this->assertEquals($dataset['value'], $model->preferences->value());

        foreach ($dataset['has'] as $has) {
            $this->assertTrue($model->preferences->has($has));
        }

        $array = $model->toArray();
        $this->assertEquals($dataset['serialized'], $array['preferences']);
    }

    public function testBitmaskOperations(): void
    {
        $model = new CastsBitmaskEnumsModel;
        $model->preferences = [BitmaskPreferenceEnum::DarkMode, BitmaskPreferenceEnum::AutoUpdates];
        $model->save();


        # test 1
        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::DarkMode));
        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates));


        # test 2
        $model->preferences = $model->preferences->set(BitmaskPreferenceEnum::LogActivity);
        $model->preferences = $model->preferences->unset(BitmaskPreferenceEnum::AutoUpdates);
        $model->save();

        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::LogActivity));
        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::DarkMode));
        $this->assertFalse($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates));


        # test 3
        $model->preferences = $model->preferences->toggle(BitmaskPreferenceEnum::AutoUpdates);
        $model->preferences = $model->preferences->toggle(BitmaskPreferenceEnum::LogActivity);
        $model->save();

        $this->assertFalse($model->preferences->has(BitmaskPreferenceEnum::LogActivity));
        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::DarkMode));
        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates));


        # test 4
        $model = CastsBitmaskEnumsModel::find($model->id);

        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::DarkMode));
        $this->assertTrue($model->preferences->has(BitmaskPreferenceEnum::AutoUpdates));
        $this->assertFalse($model->preferences->has(BitmaskPreferenceEnum::LogActivity));
        $this->assertEquals(24, $model->preferences->value());
    }


    # get
    public function testGetReturnsBitmaskForValidValue()
    {
        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);


        # test 1
        $result = $cast->get($model, 'preferences', 16, []);
        $this->assertEquals(16, $result->value());
        $this->assertTrue($result->has(BitmaskPreferenceEnum::AutoUpdates));


        # test 2
        $result = $cast->get($model, 'preferences', 17, []);
        $this->assertEquals(17, $result->value());
        $this->assertTrue($result->has(BitmaskPreferenceEnum::LogActivity));
        $this->assertTrue($result->has(BitmaskPreferenceEnum::AutoUpdates));


        # test 3
        $result = $cast->get($model, 'preferences', '3', []);
        $this->assertEquals(3, $result->value());
        $this->assertTrue($result->has(BitmaskPreferenceEnum::LogActivity));
        $this->assertTrue($result->has(BitmaskPreferenceEnum::PushNotification));


        # test 4
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::LogActivity,
            BitmaskPreferenceEnum::DarkMode,
        );

        $result = $cast->get($model, 'preferences', $value, []);
        $this->assertEquals(9, $result->value());
        $this->assertEquals($result, $value);
        $this->assertTrue($result->has(BitmaskPreferenceEnum::LogActivity));
        $this->assertTrue($result->has(BitmaskPreferenceEnum::DarkMode));
    }


    # serialize
    public function testSerializeReturnsEmptyStringForNonBitmaskValues()
    {
        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);

        $result = $cast->serialize($model, 'preferences', '', []);

        $this->assertEquals('', $result);
    }

    public function testSerializeReturnsCorrectStringForBitmaskValues()
    {
        $model = new CastsBitmaskEnumsModel;
        $enum = BitmaskPreferenceEnum::class;
        $cast = new AsBitmask($enum);


        # test 1
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::LogActivity,
            BitmaskPreferenceEnum::DarkMode,
        );

        $result = $cast->serialize($model, 'preferences', $value, []);
        $this->assertEquals('LogActivity,DarkMode', $result);


        # test 2
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::AutoUpdates,
        );

        $result = $cast->serialize($model, 'preferences', $value, []);
        $this->assertEquals('AutoUpdates', $result);


        # test 3
        $value = BitmaskPreferenceEnum::mask(
            BitmaskPreferenceEnum::LogActivity,
            BitmaskPreferenceEnum::DarkMode,
            BitmaskPreferenceEnum::DataExport,
        );

        $result = $cast->serialize($model, 'preferences', $value, []);
        $this->assertEquals('LogActivity,DarkMode,DataExport', $result);
    }


    # datasets
    public static function modelTestDataset(): array
    {
        return [
            [
                # dataset 1
                [
                    'preferences' => BitmaskPreferenceEnum::AutoUpdates,
                    'value'       => 16,
                    'serialized'  => 'AutoUpdates',
                    'has'         => [
                        BitmaskPreferenceEnum::AutoUpdates
                    ]
                ],

                # dataset 2
                [
                    'preferences' => 'DarkMode',
                    'value'       => 8,
                    'serialized'  => 'DarkMode',
                    'has'         => [
                        BitmaskPreferenceEnum::DarkMode
                    ]
                ],

                # dataset 3
                [
                    'preferences' => 'DataExport,TwoFactorAuth,PushNotification',
                    'value'       => 38,
                    'serialized'  => 'DataExport,TwoFactorAuth,PushNotification',
                    'has'         => [
                        BitmaskPreferenceEnum::DataExport,
                        BitmaskPreferenceEnum::TwoFactorAuth,
                        BitmaskPreferenceEnum::PushNotification,
                    ]
                ],

                # dataset 4
                [
                    'preferences' => ' DataExport, TwoFactorAuth ,DarkMode ',
                    'value'       => 44,
                    'serialized'  => 'DataExport,TwoFactorAuth,DarkMode',
                    'has'         => [
                        BitmaskPreferenceEnum::DataExport,
                        BitmaskPreferenceEnum::TwoFactorAuth,
                        BitmaskPreferenceEnum::DarkMode,
                    ]
                ],

                # dataset 5
                [
                    'preferences' => '',
                    'value'       => 0,
                    'serialized'  => '',
                    'has'         => []
                ],

                # dataset 6
                [
                    'preferences' => [
                        BitmaskPreferenceEnum::DarkMode,
                        BitmaskPreferenceEnum::LogActivity,
                    ],
                    'value'       => 9,
                    'serialized'  => 'DarkMode,LogActivity',
                    'has'         => [
                        BitmaskPreferenceEnum::DarkMode,
                        BitmaskPreferenceEnum::LogActivity,
                    ]
                ],

                # dataset 7
                [
                    'preferences' => BitmaskPreferenceEnum::mask(
                        BitmaskPreferenceEnum::TwoFactorAuth,
                        BitmaskPreferenceEnum::LogActivity,
                    ),
                    'value'       => 5,
                    'serialized'  => 'TwoFactorAuth,LogActivity',
                    'has'         => [
                        BitmaskPreferenceEnum::TwoFactorAuth,
                        BitmaskPreferenceEnum::LogActivity,
                    ]
                ],

                # dataset 8
                [
                    'preferences' => BitmaskPreferenceEnum::mask(),
                    'value'       => 0,
                    'serialized'  => '',
                    'has'         => []
                ],

                # dataset 8
                [
                    'preferences' => [],
                    'value'       => 0,
                    'serialized'  => '',
                    'has'         => []
                ],
            ]
        ];
    }
}
