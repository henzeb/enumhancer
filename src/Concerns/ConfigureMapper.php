<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Helpers\Mappers\EnumMapper;

trait ConfigureMapper
{
    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setMapper(Mapper|array|string|null ...$mapper): void
    {
        EnumMapper::checkMappers(self::class, ...$mapper);

        EnumProperties::store(
            self::class,
            EnumProperties::reservedWord('mapper'),
            $mapper,
            true
        );
    }

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setMapperOnce(Mapper|array|string|null ...$mapper): void
    {
        EnumMapper::checkMappers(self::class, $mapper);

        EnumProperties::storeOnce(
            self::class,
            EnumProperties::reservedWord('mapper'),
            $mapper,
            true
        );
    }
}
