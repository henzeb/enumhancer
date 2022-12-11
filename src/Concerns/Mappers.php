<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumMapper;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Helpers\EnumExtractor;
use Henzeb\Enumhancer\Helpers\EnumProperties;

trait Mappers
{
    protected static function reporter(): ?Reporter
    {
        return EnumReporter::get();
    }

    protected static function mapper(): ?Mapper
    {
        return EnumProperties::get(
            self::class,
            EnumProperties::reservedWord('mapper')
        );
    }

    public static function make(string|int|null $value, Mapper|string $mapper = null): self
    {
        return EnumMakers::make(
            self::class,
            EnumMapper::map(self::class, $value, $mapper, self::mapper())
        );
    }

    public static function tryMake(string|int|null $value, Mapper|string $mapper = null): ?self
    {
        return EnumMakers::tryMake(
            self::class,
            EnumMapper::map(self::class, $value, $mapper, self::mapper())
        );
    }

    public static function makeArray(iterable $values, Mapper|string $mapper = null): array
    {

        return EnumMakers::makeArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, $mapper, self::mapper())
        );
    }

    public static function tryMakeArray(iterable $values, Mapper|string $mapper = null): array
    {
        return EnumMakers::tryMakeArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, $mapper, self::mapper())
        );
    }

    public static function makeOrReport(
        int|string|null $value,
        BackedEnum $context = null,
        Mapper|string $mapper = null
    ): ?self {
        return EnumReporter::makeOrReport(
            self::class,
            EnumMapper::map(self::class, $value, $mapper, self::mapper()),
            $context,
            self::reporter()
        );
    }

    public static function makeOrReportArray(
        iterable $values,
        BackedEnum $context = null,
        Mapper|string $mapper = null
    ): array {
        return EnumReporter::makeOrReportArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, $mapper, self::mapper()),
            $context,
            self::reporter()
        );
    }

    public static function extract(string $text, Mapper|string $mapper = null): array
    {
        return EnumExtractor::extract(self::class, $text, $mapper, self::mapper());
    }
}
