<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumExtractor;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use Henzeb\Enumhancer\Helpers\Mappers\EnumMapper;
use UnitEnum;

trait Mappers
{
    protected static function reporter(): ?Reporter
    {
        return EnumReporter::get();
    }

    protected static function mapper(): Mapper|array|string|null
    {
        return null;
    }

    public static function get(string|int|UnitEnum|null $value, Mapper|string|array|null ...$mapper): self
    {
        return EnumGetters::get(
            self::class,
            EnumMapper::map(self::class, $value, ...[...$mapper, self::mapper()]),
        );
    }


    public static function tryGet(string|int|UnitEnum|null $value, Mapper|string|array|null ...$mapper): ?self
    {
        return EnumGetters::tryGet(
            self::class,
            EnumMapper::map(self::class, $value, ...[...$mapper, self::mapper()])
        );
    }

    public static function getArray(iterable $values, Mapper|string|array|null ...$mapper): array
    {
        return EnumGetters::getArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, ...[...$mapper, self::mapper()])
        );
    }

    public static function tryArray(iterable $values, Mapper|string|array|null ...$mapper): array
    {
        return EnumGetters::tryArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, ...[...$mapper, self::mapper()])
        );
    }

    public static function getOrReport(
        int|string|UnitEnum|null $value,
        BackedEnum|null $context = null,
        Mapper|string|array|null ...$mapper
    ): ?self {
        return EnumReporter::getOrReport(
            self::class,
            EnumMapper::map(self::class, $value, ...[...$mapper, self::mapper()]),
            $context,
            self::reporter()
        );
    }

    public static function getOrReportArray(
        iterable $values,
        BackedEnum|null $context = null,
        Mapper|string|array|null $mapper = null
    ): array {
        return EnumReporter::getOrReportArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, $mapper, self::mapper()),
            $context,
            self::reporter()
        );
    }

    public static function extract(string $text, Mapper|string|array|null ...$mapper): array
    {
        return EnumExtractor::extract(self::class, $text, ...[...$mapper, self::mapper()]);
    }
}
