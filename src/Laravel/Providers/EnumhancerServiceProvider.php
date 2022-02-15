<?php
namespace Henzeb\Enumhancer\Laravel\Providers;

use Henzeb\Enumhancer\Helpers\EnumReporter;
use Illuminate\Support\ServiceProvider;

class EnumhancerServiceProvider extends ServiceProvider
{
    public function boot() {
        EnumReporter::laravel();
    }
}
