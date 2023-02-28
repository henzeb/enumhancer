<?php

namespace Henzeb\Enumhancer\Helpers\Mappers;

use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use UnitEnum;
use ValueError;
use function array_walk;
use function is_a;
use function is_subclass_of;

/**
 * @internal
 */
final class EnumMapper
{
    /**
     * @param string $enum
     * @param string|int|UnitEnum|null $value
     * @param array<string|int, mixed>|Mapper|string|null ...$mappers
     * @return string|null
     */
    public static function map(
        string $enum,
        UnitEnum|int|string|null $value,
        array|string|Mapper|null ...$mappers
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

    /**
     * @param string $enum
     * @param iterable<string|int, mixed> $values
     * @param Mapper|string|array<string|int,string|int|UnitEnum|array<string|int,string|int|UnitEnum>>|null ...$mappers
     * @return string[]
     */
    public static function mapArray(string $enum, iterable $values, Mapper|string|array|null ...$mappers): array
    {
        $mapped = [];

        foreach ($values as $value) {
            $mapped[] = EnumMapper::map($enum, $value, ...$mappers);
        }

        return $mapped;
    }

    /**
     * @return Mapper[]
     */
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

    /**
     * @param string $enum
     * @return Mapper[]
     * @throws ReflectionException
     */
    private static function getConstantMappers(string $enum): array
    {
        /**
         * @var UnitEnum $enum
         */

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

    protected static function parseConstantAsMapper(UnitEnum|string $enum, string $name, mixed $constant): ?Mapper
    {
        if (is_array($constant)) {
            return self::wrapInMapper($constant);
        }

        if (!is_string($constant) || !class_exists($constant)) {
            return null;
        }

        self::checkMappers(is_object($enum) ? $enum::class : $enum, $constant);

        return self::instantiateMapper(
            $constant,
            str_starts_with(
                strtolower($name),
                'map_flip'
            )
        );
    }

    public static function isValidMapper(string $enum, mixed $value): bool
    {
        return self::isMapperClass($value)
            || is_array($value)
            || is_a($value, $enum);
    }

    public static function isMapperClass(mixed $mapper): bool
    {
        return is_subclass_of($mapper, Mapper::class);
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

    /**
     * @param array<string|int,string|int|UnitEnum|array<string|int,string|int|UnitEnum>> $map
     * @return Mapper
     */
    protected static function wrapInMapper(array $map): Mapper
    {
        return EnumArrayMapper::newInstance($map);
    }

    /**
     * @param string $enum
     * @return array<string|int,string|int|UnitEnum|array<string|int,string|int|UnitEnum>>
     */
    private static function getConfiguredMapper(string $enum): array
    {
        return EnumProperties::get(
            $enum,
            EnumProperties::reservedWord('mapper')
        ) ?? [];
    }

    /**
     * @param string $enum
     * @param Mapper|string|array|null ...$mappers
     * @return Mapper
     * @throws ReflectionException
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

        array_walk(
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
