<?php

namespace Henzeb\Enumhancer\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Henzeb\Enumhancer\Helpers\EnumReporter;

class EnumhancerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        EnumReporter::laravel();
    }
}
