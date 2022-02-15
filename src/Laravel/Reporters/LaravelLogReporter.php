<?php
namespace Henzeb\Enumhancer\Laravel\Reporters;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Illuminate\Support\Facades\Log;
use function class_basename;

class LaravelLogReporter implements Reporter
{

    public function report(string $enum, string $key, ?BackedEnum $context): void
    {
        Log::warning(
            class_basename($enum)
            . ' does not have \'' . $key . '\'',
            array_filter([
                'class' => class_basename($enum),
                'key' => $key,
                'context' => $context?->value
            ])
        );
    }
}
