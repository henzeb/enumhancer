<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Contracts\Mapper;

class EnumMapper
{
    public static function map(string|int|null $value, Mapper|string|null ...$mappers): ?string
    {
        if (null === $value) {
            return null;
        }

        foreach (array_filter($mappers) as $mapper) {
            $mapper = is_string($mapper) ? new $mapper() : $mapper;
            $value = $mapper->map($value, static::class) ?? $value;
        }

        return $value;
    }

    public static function mapArray(iterable $values, Mapper|string|null ...$mappers): array
    {
        $mapped = [];

        foreach ($values as $value) {
            $mapped[] = EnumMapper::map($value, ...$mappers);
        }

        return $mapped;
    }
}
