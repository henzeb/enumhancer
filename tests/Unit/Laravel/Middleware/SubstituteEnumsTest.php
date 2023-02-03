<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Middleware;


use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedIntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class SubstituteEnumsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [EnumhancerServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Config::set('app.key',
            'base64:+vvg9yApP0djYSZlVTA0y4QnzdC7icL1U5qExdW4gts='
        );

    }

    protected function defineRoutes($router)
    {
        $router->middleware('api')->get('/simpleapi/{status}',
            function (SimpleEnum $status) {
                $this->assertEquals(SimpleEnum::Open, $status);
            }
        );
    }

    protected function defineWebRoutes($router)
    {
        $router->get('/noparams',
            function () {
                return null;
            }
        );

        $router->get('/backed/{status}',
            function (EnhancedBackedEnum $status) {
                return $status->name;
            }
        );

        $router->get('/intbacked/{status}',
            function (EnhancedIntBackedEnum $status) {
            }
        );

        $router->get('/simple/{status}',
            function (SimpleEnum $status) {
            }
        );

        $router->get('/optional/{status?}',
            function (SimpleEnum $status = null) {
                return $status?->name;
            }
        );

        $router->get('/default/{status?}',
            function (DefaultsEnum $status) {
                $this->assertEquals(DefaultsEnum::Default, $status);
            }
        );
    }

    public function testApiRouteNoParameters()
    {
        $this->get('/noparams')->assertOk();
    }

    public function testApiRouteBindsBasicEnum()
    {
        $this->get('/simpleapi/open')->assertOk();
    }

    public function testShouldBindBasicEnum()
    {
        $this->get('/simple/open')->assertOk();
        $this->get('/simple/Open')->assertOk();
        $this->get('/simple/0')->assertOk();
    }

    public function testShouldBindBasicEnumOptionally()
    {
        $this->get('/optional/')->assertOk()->assertSee('');
        $this->get('/optional/open')->assertOk()->assertSee('Open');
    }

    public function testShouldBindBasicEnumWithDefault()
    {
        $this->get('/default/')->assertOk();
    }

    public function testShouldBindBackedEnum()
    {
        $this->get('/backed/third_enum')
            ->assertOk()->assertSeeText('ENUM_3');
        $this->get('/backed/ConstantEnum')->assertOk()
            ->assertSeeText('ENUM_3');
        $this->get('/backed/0')->assertOk()
            ->assertSeeText('ENUM');

        $this->get('/backed/Failed')->assertNotFound();
    }

    public function testShouldBindIntBackedEnum()
    {
        $this->get('/intbacked/Open')->assertOk();
        $this->get('/intbacked/ConstantEnum')->assertOk();
        $this->get('/intbacked/0')->assertOk();

        $this->get('/intbacked/99')->assertOk();

        $this->get('/intbacked/Failed')->assertNotFound();
    }
}
