<?php

namespace Henzeb\Enumhancer\Laravel\Reporters;

use BackedEnum;
use Illuminate\Support\Facades\Log;
use Henzeb\Enumhancer\Contracts\Reporter;
use function class_basename;

class LaravelLogReporter implements Reporter
{

    public function report(string $enum, ?string $key, ?BackedEnum $context): void
    {
        Log::warning(
            class_basename($enum)
            . ($key ? ' does not have \'' . $key . '\'' : ': A null value was passed'),
            array_filter([
                'class' => class_basename($enum),
                'key' => $key,
                'context' => $context?->value
            ])
        );
    }
}
