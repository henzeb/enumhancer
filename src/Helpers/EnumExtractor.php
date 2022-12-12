<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Concerns\Mappers;

abstract class EnumExtractor
{
    use Mappers;

    public static function extract(string $class, string $text, Mapper|string|null ...$mappers): array
    {
        EnumCheck::check($class);

        $mappers = EnumMapper::sanitizeMapperArray(...$mappers);
        /**
         * @var $class UnitEnum|string
         */

        $match = array_map(
            function ($enum) {

                if (property_exists($enum, 'value')) {
                    return $enum->value;
                }

                return $enum->name;
            },
            $class::cases()
        );
        $match = implode(
            '\b|\b',
            array_merge(
                $match,
                ...array_map(fn(Mapper $map) => $map->keys($class), $mappers)
            )
        );

        preg_match_all(sprintf('/\b%s\b/i', $match), $text, $matches);

        $matches = array_map(fn($value) => EnumMapper::map($class, $value, ...$mappers), $matches[0] ?? []);

        return EnumGetters::getArray($class, $matches);
    }
}
