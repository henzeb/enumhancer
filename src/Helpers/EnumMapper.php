<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Contracts\Mapper;
use ReflectionClass;
use RuntimeException;
use UnitEnum;

abstract class EnumMapper
{
    public static function map(
        string $enum,
        string|int|UnitEnum|null $value,
        Mapper|array|string|null ...$mappers
    ): ?string {
        EnumCheck::check($enum);

        if (null === $value) {
            return null;
        }

        $mappers = self::sanitizeMapperArray(...$mappers);

        self::constantMappers($enum, $mappers);

        foreach ($mappers as $mapper) {
            $value = $mapper->map($value, $enum) ?? $value;
        }

        return $value instanceof UnitEnum ? $value->name : $value;
    }

    public static function mapArray(string $enum, iterable $values, Mapper|string|array|null ...$mappers): array
    {
        $mapped = [];

        foreach ($values as $value) {
            $mapped[] = EnumMapper::map($enum, $value, ...$mappers);
        }

        return $mapped;
    }

    public static function sanitizeMapperArray(Mapper|string|array|null ...$mappers): array
    {
        return
            array_map(
                function (Mapper|array|string $mapper) {

                    if (is_string($mapper)) {
                        $mapper = new $mapper;
                    }

                    if (is_array($mapper)) {
                        $mapper = self::wrapInMapper($mapper);
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

    private static function constantMappers(string $enum, array &$mappers)
    {
        $constants = (new ReflectionClass($enum))->getConstants();

        foreach ($constants as $name => $constant) {
            if (str_starts_with(strtolower($name), 'map') && is_array($constant)) {
                $mappers[] = self::wrapInMapper($constant);
            }
        }
    }

    protected static function wrapInMapper(array $map): Mapper
    {
        return new EnumArrayMapper($map);
    }
}
