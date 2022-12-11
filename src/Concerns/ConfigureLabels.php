<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumProperties;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;
use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;

trait ConfigureLabels
{
    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setLabels(array $labels): void
    {
        EnumProperties::store(
            self::class,
            EnumProperties::reservedWord('labels'),
            $labels,
            true,
        );
    }

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function setLabelsOnce(array $labels): void
    {
        EnumProperties::storeOnce(
            self::class,
            EnumProperties::reservedWord('labels'),
            $labels,
            true,
        );
    }
}
