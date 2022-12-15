<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;

trait ConfigureMapper
{
    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setMapper(Mapper|array|null $mapper): void
    {
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
    public static function setMapperOnce(Mapper|array $mapper): void
    {
        EnumProperties::storeOnce(
            self::class,
            EnumProperties::reservedWord('mapper'),
            $mapper,
            true
        );
    }
}
