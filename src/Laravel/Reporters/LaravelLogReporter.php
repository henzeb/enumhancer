<?php

namespace Henzeb\Enumhancer\Laravel\Reporters;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Enums\LogLevel;
use Illuminate\Support\Facades\Log;
use function class_basename;

class LaravelLogReporter implements Reporter
{
    private readonly ?LogLevel $level;

    /**
     * @var string[]
     */
    private array $channels;

    public function __construct(
        ?LogLevel $level = null,
        string ...$channels
    ) {
        $this->level = $level;
        $this->channels = $channels;
    }

    private function getLevel(): string
    {
        return (string)($this->level ?? LogLevel::default() ?? LogLevel::Notice)->value();
    }

    /**
     * @return string[]
     */
    private function getChannels(): array
    {
        if (empty($this->channels)) {
            return [config('logging.default')];
        }

        return $this->channels;
    }

    public function report(string $enum, ?string $key, ?BackedEnum $context): void
    {
        Log::stack(
            $this->getChannels()
        )->log(
            $this->getLevel(),
            class_basename($enum)
            . ($key ? sprintf(' does not have \'%s\'', $key) : ': A null value was passed'),
            array_filter(
                [
                    'class' => $enum,
                    'key' => $key,
                    'context' => $context?->value
                ]
            )
        );
    }
}
