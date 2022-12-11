<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;

trait ConfigureDefaults
{
    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setDefault(?self $default): void
    {
        $reservedWord = EnumProperties::reservedWord('defaults');

        EnumProperties::store(
            self::class,
            $reservedWord,
            $default,
            true
        );
    }

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setDefaultOnce(self $default): void
    {
        $reservedWord = EnumProperties::reservedWord('defaults');

        EnumProperties::storeOnce(
            self::class,
            $reservedWord,
            $default,
            true
        );
    }
}
