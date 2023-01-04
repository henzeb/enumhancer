<?php

namespace Henzeb\Enumhancer\Laravel\Providers;

use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Laravel\Mixins\RulesMixin;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Illuminate\Validation\Rule;

class EnumhancerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->setupReporter();

        $this->setupRules();
    }

    protected function setupReporter(): void
    {
        LogLevel::setDefault(LogLevel::Notice);

        EnumReporter::laravel();
    }

    private function setupRules(): void
    {
        Rule::mixin(new RulesMixin());
    }
}
