<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Mixins;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Orchestra\Testbench\TestCase;

class FormRequestMixinTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EnumhancerServiceProvider::class
        ];
    }

    public function testAsEnum()
    {
        $request = new FormRequest(
            [
                'myEnum' => 'open',
                'myInvalidEnum' => 'invalid',
                'myNullEnum' => null,
            ]
        );

        $this->assertEquals(
            SimpleEnum::Open,
            $request->asEnum('myEnum', SimpleEnum::class)
        );

        $this->assertNull(
            $request->asEnum('myInvalidEnum', SimpleEnum::class)
        );

        $this->assertNull(
            $request->asEnum('myDoesNotExistEnum', SimpleEnum::class)
        );

        $this->assertNull(
            $request->asEnum('myNullEnum', SimpleEnum::class)
        );
    }

    public function testAsEnumDefault()
    {
        $request = new FormRequest([
            'myEnum' => 'default',
            'myInvalidEnum' => 'invalid',
            'NullEnum' => null,
        ]);

        $this->assertEquals(
            DefaultsEnum::default(),
            $request->asEnum('myEnum', DefaultsEnum::class)
        );

        $this->assertEquals(
            DefaultsEnum::default(),
            $request->asEnum('myInvalidEnum', DefaultsEnum::class)
        );

        $this->assertEquals(
            DefaultsEnum::default(),
            $request->asEnum('myDoesNotExistEnum', DefaultsEnum::class)
        );

        $this->assertEquals(
            DefaultsEnum::default(),
            $request->asEnum('nullEnum', DefaultsEnum::class)
        );
    }

    public function testAsEnumWithMapper()
    {
        $request = new FormRequest([
            'myEnum' => 'opened',
        ]);

        $this->assertEquals(
            SimpleEnum::Open,
            $request->asEnum('myEnum', SimpleEnum::class, ['opened' => 'open'])
        );

        $this->assertEquals(
            SimpleEnum::Open,
            $request->asEnum('myEnum', SimpleEnum::class, ['opened' => 'opening'], ['opening' => 'open'])
        );

        $mapper = new class extends Mapper
        {
            protected function mappable(): array
            {
                return [
                    'opened'=>'opening'
                ];
            }
        };

        $this->assertEquals(
            SimpleEnum::Open,
            $request->asEnum('myEnum', SimpleEnum::class, $mapper, ['opening'=>'open'])
        );

        $this->assertEquals(
            SimpleEnum::Open,
            $request->asEnum('myEnum', SimpleEnum::class, $mapper::class, ['opening'=>'open'])
        );

    }
}
