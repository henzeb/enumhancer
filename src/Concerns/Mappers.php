<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Contracts\Reporter;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumReporter;

trait Mappers
{
    protected static function reporter():?Reporter
    {
        return EnumReporter::get();
    }

    protected static function mapper(): ?Mapper
    {
        return null;
    }

    private static function map(string|int|null $value, Mapper|string $mapper = null): ?string
    {
        if(null === $value) {
            return null;
        }

        $value = ($mapper) ?
            $mapper->map($value, static::class) ?? $value
            : $value;

        return ($mapper = self::mapper()) ?
            $mapper->map($value, static::class) ?? $value
            : $value;
    }

    final public static function make(string|int|null $value, Mapper|string $mapper = null): self
    {
        return EnumMakers::make(self::class, self::map($value, $mapper));
    }

    final public static function tryMake(string|int|null $value, Mapper|string $mapper = null): ?self
    {
        return EnumMakers::tryMake(self::class, self::map($value, $mapper));
    }

    final public static function makeArray(iterable $values, Mapper|string $mapper = null): array
    {
        $mapped = [];
        foreach($values as $value) {
            $mapped[] = self::map($value, $mapper);
        }
        return EnumMakers::makeArray(self::class, $mapped);
    }

    final public static function tryMakeArray(iterable $values, Mapper|string $mapper = null): array
    {
        $mapped = [];
        foreach($values as $value) {
            $mapped[] = self::map($value, $mapper);
        }
        return EnumMakers::tryMakeArray(self::class, $mapped);
    }

    final public static function makeOrReport(int|string|null $values, BackedEnum $context = null, Mapper $mapper = null): ?self
    {
        return EnumReporter::makeOrReport(self::class, self::map($values, $mapper), $context, self::reporter());
    }

    public static function makeOrReportArray(iterable $values, BackedEnum $context = null, Mapper $mapper = null): array
    {
        $mapped = [];
        foreach($values as $value) {
            $mapped[] = self::map($value, $mapper);
        }

        return EnumReporter::makeOrReportArray(self::class, $mapped, $context, self::reporter());
    }

}
