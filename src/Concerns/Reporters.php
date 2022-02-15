<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumReporter;

trait Reporters
{
    final protected static function reporter(): ?Reporter
    {
        return EnumReporter::get();
    }

    final public static function makeOrReport(int|string|null $key, BackedEnum $context = null): ?self
    {
        return EnumReporter::makeOrReport(self::class, $key, $context, self::reporter());
    }

    final public static function makeOrReportArray(iterable $keys, BackedEnum $context = null): array
    {
        return EnumReporter::makeOrReportArray(self::class, $keys, $context, self::reporter());
    }
}
