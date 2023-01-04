<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Mappers;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\Mappers\EnumMapper;
use UnitEnum;

/**
 * @internal
 */
final class EnumExtractor
{
    use Mappers;

    public static function extract(string $class, string $text, Mapper|string|array|null ...$mappers): array
    {
        EnumCheck::check($class);

        $mappers = EnumMapper::getMappers($class, ...$mappers);
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
