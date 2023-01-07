<?php

namespace Henzeb\Enumhancer\Laravel\Providers;

use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Laravel\Middleware\SubstituteEnums;
use Henzeb\Enumhancer\Laravel\Mixins\FormRequestMixin;
use Henzeb\Enumhancer\Laravel\Mixins\RulesMixin;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

class EnumhancerServiceProvider extends ServiceProvider
{
    public function boot(Kernel $kernel): void
    {
        $this->setupReporter();

        $this->setupMacroMixins();

        $this->setupEnumBindingMiddleware($kernel);
    }

    protected function setupReporter(): void
    {
        LogLevel::setDefault(LogLevel::Notice);

        EnumReporter::laravel();
    }

    private function setupMacroMixins(): void
    {
        Rule::mixin(new RulesMixin());
        FormRequest::mixin(new FormRequestMixin());
    }

    protected function setupEnumBindingMiddleware(Kernel $kernel): void
    {
        /**
         * @var \Illuminate\Foundation\Http\Kernel $kernel
         */
        $kernel->prependToMiddlewarePriority(SubstituteEnums::class);
        $kernel->appendMiddlewareToGroup('web', SubstituteEnums::class);
        $kernel->appendMiddlewareToGroup('api', SubstituteEnums::class);
    }
}
