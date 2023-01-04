<?php

namespace Henzeb\Enumhancer\Helpers\Mappers;

use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use ReflectionClass;
use RuntimeException;
use UnitEnum;
use ValueError;

/**
 * @internal
 */
final class EnumMapper
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

        foreach (self::getMappers($enum, ...$mappers) as $mapper) {
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

    private static function sanitizeMapperArray(array $mappers): array
    {
        return array_map(
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

    private static function getConstantMappers(string $enum): array
    {
        $mappers = [];
        $constants = (new ReflectionClass($enum))->getConstants();

        foreach ($constants as $name => $constant) {
            if (!str_starts_with(strtolower($name), 'map')) {
                continue;
            }

            $mappers[] = self::parseConstantAsMapper($enum, $name, $constant);
        }

        return $mappers;
    }

    protected static function parseConstantAsMapper(string $enum, string $name, mixed $constant): ?Mapper
    {
        if (is_array($constant)) {
            return self::wrapInMapper($constant);
        }

        if (!is_string($constant) || !class_exists($constant)) {
            return null;
        }

        self::checkMappers($enum, $constant);

        return self::instantiateMapper(
            $constant,
            str_starts_with(
                strtolower($name),
                'map_flip'
            )
        );
    }

    public static function isMapperClass(mixed $mapper): bool
    {
        return is_a($mapper, Mapper::class, true);
    }

    protected static function instantiateMapper(string $class, bool $flip = false): Mapper
    {
        /**
         * @var $class Mapper
         */
        if ($flip) {
            return $class::flip();
        }

        return $class::newInstance();
    }

    protected static function wrapInMapper(array $map): Mapper
    {
        return EnumArrayMapper::newInstance($map);
    }

    private static function getConfiguredMapper(string $enum): array
    {
        return EnumProperties::get(
            $enum,
            EnumProperties::reservedWord('mapper')
        ) ?? [];
    }

    /**
     * @param string $enum
     * @param array $mappers
     * @return array
     */
    public static function getMappers(string $enum, Mapper|string|array|null ...$mappers): array
    {
        /**
         * @var $enum Mappers|UnitEnum|String
         */
        return self::sanitizeMapperArray(
            [
                ...$mappers,
                ...self::getConfiguredMapper($enum),
                ...self::getConstantMappers($enum)
            ]
        );
    }

    public static function checkMappers(string $enum, mixed ...$mappers): void
    {
        EnumCheck::check($enum);

        \array_walk(
            $mappers,
            function ($mapper) {
                if (is_string($mapper) && !EnumMapper::isMapperClass($mapper)) {
                    throw new ValueError(
                        sprintf('Invalid class. expected Mapper, %s given', $mapper)
                    );
                }
            }
        );
    }
}
