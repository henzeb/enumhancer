<?php

namespace Henzeb\Enumhancer\Tests;

use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedIntBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        EnumReporter::set(null);
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [EnumhancerServiceProvider::class];
    }

    protected function defineRoutes($router)
    {
        // For SubstituteEnumsTest
        $router->middleware('api')->get('/simpleapi/{status}',
            function (SimpleEnum $status) {
                test()->assertEquals(SimpleEnum::Open, $status);
            }
        );
    }

    protected function defineWebRoutes($router)
    {
        // For SubstituteEnumsTest
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
            function (SimpleEnum|null $status = null) {
                return $status?->name;
            }
        );

        $router->get('/default/{status?}',
            function (DefaultsEnum $status) {
                test()->assertEquals(DefaultsEnum::Default, $status);
            }
        );
    }
}