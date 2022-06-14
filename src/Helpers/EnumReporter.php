<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use RuntimeException;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;

abstract class EnumReporter
{
    private static Reporter|string|null $reporter = null;

    public static function laravel(): void
    {
        self::set(LaravelLogReporter::class);
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

    public static function makeOrReport(string $class, $key, ?BackedEnum $context, ?Reporter $reporter)
    {
        $enum = EnumMakers::tryMake($class, $key);

        if (!$enum) {
            $reporter?->report($class, $key, $context);
        }

        return $enum;
    }

    public static function makeOrReportArray(string $class, iterable $keys, ?BackedEnum $context, ?Reporter $reporter)
    {
        EnumCheck::check($class);

        $result = [];

        foreach ($keys as $key) {
            $result[] = self::makeOrReport($class, $key, $context, $reporter);
        }

        return array_filter($result);
    }
}
