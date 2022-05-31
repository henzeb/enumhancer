<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Concerns\Mappers;

class EnumExtractor
{
    use Mappers;

    public static function extract(string $class, string $text, Mapper ...$mappers): array
    {
        EnumCheck::check($class);

        /**
         * @var $class UnitEnum|string
         */

        $match = array_map(
            function ($enum) {

                if (property_exists($enum, 'value')) {
                    return $enum->value;
                }

                return $enum->name;
            }, $class::cases()
        );

        $match = implode(
            '|',
            array_merge(
                $match,
                ...array_map(fn(Mapper $map) => $map->keys($class), $mappers)
            ));

        preg_match_all(sprintf('/%s/i', $match), $text, $matches);

        $matches = array_map(fn($value) => EnumMapper::map($value, ...$mappers), $matches[0] ?? []);

        return EnumMakers::makeArray($class, $matches);
    }
}
