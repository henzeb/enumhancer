<?php

namespace Henzeb\Enumhancer\Helpers;

use RuntimeException;
use Henzeb\Enumhancer\Contracts\Mapper;

abstract class EnumMapper
{
    public static function map(string $enum, string|int|null $value, Mapper|string|null ...$mappers): ?string
    {
        EnumCheck::check($enum);

        if (null === $value) {
            return null;
        }

        $mappers = self::sanitizeMapperArray(...$mappers);

        foreach ($mappers as $mapper) {
            $value = $mapper->map($value, $enum) ?? $value;
        }

        return $value;
    }

    public static function mapArray(string $enum, iterable $values, Mapper|string|null ...$mappers): array
    {
        $mapped = [];

        foreach ($values as $value) {
            $mapped[] = EnumMapper::map($enum, $value, ...$mappers);
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
                            sprintf(
                                'object of type \'%s\' expected, got \'%s\'',
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
