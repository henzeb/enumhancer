<?php

namespace Henzeb\Enumhancer\Laravel\Providers;

use Henzeb\Enumhancer\Enums\LogLevel;
use Illuminate\Support\ServiceProvider;
use Henzeb\Enumhancer\Helpers\EnumReporter;

class EnumhancerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        LogLevel::setDefault(LogLevel::Notice);

        EnumReporter::laravel();
    }
}
