<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;
use RuntimeException;
use UnitEnum;

/**
 * @internal
 */
final class EnumReporter
{
    private static Reporter|string|null $reporter = null;

    public static function laravel(LogLevel $logLevel = null, string ...$channels): void
    {
        self::set(new LaravelLogReporter($logLevel, ...$channels));
    }

    public static function set(Reporter|string|null $reporter): void
    {
        if (!is_null($reporter) && !is_subclass_of($reporter, Reporter::class)) {
            throw new RuntimeException($reporter . ' is not a ' . Reporter::class);
        }

        static::$reporter = $reporter;
    }

    public static function get(): ?Reporter
    {
        if (empty(self::$reporter)) {
            return null;
        }

        if (is_string(self::$reporter)) {
            return new (self::$reporter)();
        }
        return self::$reporter;
    }

    public static function getOrReport(string $class, $key, ?BackedEnum $context, ?Reporter $reporter): ?UnitEnum
    {
        $enum = EnumGetters::tryGet($class, $key);

        if (!$enum) {
            $reporter?->report($class, $key, $context);
        }

        return $enum;
    }

    public static function getOrReportArray(
        string $class,
        iterable $keys,
        ?BackedEnum $context,
        ?Reporter $reporter
    ): array {
        EnumCheck::check($class);

        $result = [];

        foreach ($keys as $key) {
            $result[] = self::getOrReport($class, $key, $context, $reporter);
        }

        return array_filter($result);
    }
}
