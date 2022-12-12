<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumExtractor;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumMapper;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Helpers\EnumReporter;
use UnitEnum;

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

    /**
     * @deprecated
     */
    public static function make(string|int|UnitEnum|null $value, Mapper|string $mapper = null): self
    {
        return self::get(
            $value,
            $mapper
        );
    }

    public static function get(string|int|UnitEnum|null $value, Mapper|string $mapper = null): self
    {
        return EnumGetters::get(
            self::class,
            EnumMapper::map(self::class, $value, $mapper, self::mapper())
        );
    }

    /**
     * @deprecated
     */
    public static function tryMake(string|int|UnitEnum|null $value, Mapper|string $mapper = null): ?self
    {
        return self::tryGet(
            $value,
            $mapper
        );
    }

    public static function tryGet(string|int|UnitEnum|null $value, Mapper|string $mapper = null): ?self
    {
        return EnumGetters::tryGet(
            self::class,
            EnumMapper::map(self::class, $value, $mapper, self::mapper())
        );
    }

    /**
     * @deprecated
     */
    public static function makeArray(iterable $values, Mapper|string $mapper = null): array
    {

        return self::getArray(
            $values,
            $mapper
        );
    }

    public static function getArray(iterable $values, Mapper|string $mapper = null): array
    {
        return EnumGetters::getArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, $mapper, self::mapper())
        );
    }

    /**
     * @deprecated
     */
    public static function tryMakeArray(iterable $values, Mapper|string $mapper = null): array
    {
        return self::tryArray(
            $values,
            $mapper
        );
    }

    public static function tryArray(iterable $values, Mapper|string $mapper = null): array
    {
        return EnumGetters::tryArray(
            self::class,
            EnumMapper::mapArray(self::class, $values, $mapper, self::mapper())
        );
    }

    /**
     * @deprecated
     */
    public static function makeOrReport(
        int|string|UnitEnum|null $value,
        BackedEnum $context = null,
        Mapper|string $mapper = null
    ): ?self {
        return self::getOrReport(
            $value,
            $context,
            $mapper
        );
    }

    public static function getOrReport(
        int|string|UnitEnum|null $value,
        BackedEnum $context = null,
        Mapper|string $mapper = null
    ): ?self {
        return EnumReporter::getOrReport(
            self::class,
            EnumMapper::map(self::class, $value, $mapper, self::mapper()),
            $context,
            self::reporter()
        );
    }

    /**
     * @deprecated
     */
    public static function makeOrReportArray(
        iterable $values,
        BackedEnum $context = null,
        Mapper|string $mapper = null
    ): array {
        return self::getOrReportArray(
            $values,
            $context,
            $mapper,
        );
    }

    public static function getOrReportArray(
        iterable $values,
        BackedEnum $context = null,
        Mapper|string $mapper = null
    ): array {
        return EnumReporter::getOrReportArray(
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
