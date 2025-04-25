<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumReporter;

trait Reporters
{
    protected static function reporter(): ?Reporter
    {
        return EnumReporter::get();
    }

    public static function getOrReport(int|string|null $key, ?BackedEnum $context = null): ?self
    {
        return EnumReporter::getOrReport(self::class, $key, $context, self::reporter());
    }

    public static function getOrReportArray(iterable $keys, ?BackedEnum $context = null): array
    {
        return EnumReporter::getOrReportArray(self::class, $keys, $context, self::reporter());
    }
}
