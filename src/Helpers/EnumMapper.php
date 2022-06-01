<?php

namespace Henzeb\Enumhancer\Helpers;

use RuntimeException;
use Henzeb\Enumhancer\Contracts\Mapper;

class EnumMapper
{
    public static function map(string|int|null $value, Mapper|string|null ...$mappers): ?string
    {
        if (null === $value) {
            return null;
        }

        $mappers = self::sanitizeMapperArray(...$mappers);

        foreach ($mappers as $mapper) {
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

    public static function sanitizeMapperArray(Mapper|string|null ...$mappers): array
    {
        return
            array_map(
                function (Mapper|string $mapper) {

                    if (is_string($mapper)) {
                        $mapper = new $mapper;
                    }

                    if (!$mapper instanceof Mapper) {
                        throw new RuntimeException(
                            sprintf('object of type \'%s\' expected, got \'%s\'',
                                Mapper::class,
                                $mapper::class
                            )
                        );
                    }
                    return $mapper;
                },
                array_filter($mappers)
        );
    }
}
