<?php

namespace Henzeb\Enumhancer\Tests;

use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;

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
}