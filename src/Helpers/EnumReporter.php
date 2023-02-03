<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Enums\LogLevel;
use Henzeb\Enumhancer\Laravel\Reporters\LaravelLogReporter;
use RuntimeException;
use UnitEnum;
use function is_null;
use function is_object;
use function sprintf;

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
            throw new RuntimeException(
                sprintf(
                    '%s is not a %s',
                    is_object($reporter) ? $reporter::class : $reporter,
                    Reporter::class
                )
            );
        }

        static::$reporter = $reporter;
    }

    public static function get(): ?Reporter
    {
        if (empty(self::$reporter)) {
            return null;
        }

        if (is_string(self::$reporter)) {
            /**
             * @var Reporter $reporter ;
             */
            $reporter = new (self::$reporter)();
            self::$reporter = $reporter;
        }

        return self::$reporter;
    }

    public static function getOrReport(
        string $class,
        int|string|UnitEnum|null $key,
        ?BackedEnum $context,
        ?Reporter $reporter
    ): mixed {
        $enum = EnumGetters::tryGet($class, $key, useDefault: false);

        if (!$enum) {
            if ($key instanceof UnitEnum) {
                $key = $key->name;
            }

            if (!is_null($key)) {
                $key = (string)$key;
            }

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
