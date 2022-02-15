<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;
use RuntimeException;

class EnumReporter
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

        self::$reporter = $reporter;
    }

    public static function get(): ?Reporter
    {
        if (is_string(self::$reporter)) {
            return new self::$reporter();
        }
        return self::$reporter;
    }

    public static function makeOrReport(string $class, $key, ?BackedEnum $context, ?Reporter $reporter) {

        if (!$enum = EnumMakers::tryMake($class, $key)) {
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
