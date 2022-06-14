<?php

namespace Henzeb\Enumhancer\Functions;

use UnitEnum;
use Henzeb\Enumhancer\Helpers\EnumProxy;
use Henzeb\Enumhancer\Helpers\EnumValue;

/**
 * returns a proxy object with a value property equal to it's name when given enum is not backed
 * @param UnitEnum|null $enum
 * @param bool $keepValueCase
 * @return EnumProxy|null
 */
function b(?UnitEnum $enum, bool $keepValueCase = true): ?EnumProxy
{
    if (!$enum) {
        return null;
    }

    return new EnumProxy($enum, $keepValueCase);
}

function backing(?UnitEnum $enum, bool $keepValueCase = true): ?EnumProxy
{
    return b($enum, $keepValueCase);
}

/**
 * returns a proxy object with a lower cased value equal to it's name when given enum is not backed
 *
 * @param UnitEnum|null $enum
 * @return EnumProxy|null
 */
function bl(?UnitEnum $enum): ?EnumProxy
{
    return b($enum, false);
}

function backingLowercase(?UnitEnum $enum): ?EnumProxy
{
    return bl($enum);
}

/**
 * returns the name of the given enum. Useful when using it as an array key.
 *
 * @param UnitEnum|null $enum
 * @return string|null
 */
function n(?UnitEnum $enum): ?string
{
    return $enum?->name;
}

function name(?UnitEnum $enum): ?string
{
    return n($enum);
}

/**
 * Returns a value equal to it's name when given enum is not backed by default
 *
 * @param UnitEnum|null $enum
 * @param bool $keepValueCase returns a lower cased enum name when it's a UnitEnum
 * @return string|int|null
 */
function v(?UnitEnum $enum, bool $keepValueCase = true): string|int|null
{
    if (!$enum) {
        return null;
    }
    return EnumValue::value($enum, $keepValueCase);
}


function value(?UnitEnum $enum, bool $keepValueCase = true): string|int|null
{
    return v($enum, $keepValueCase);
}

/**
 * Returns a lowercase value equal to it's name when given enum is not backed.
 *
 * @param UnitEnum|null $enum
 * @return string|int|null
 */
function vl(?UnitEnum $enum): string|int|null
{
    return value($enum, false);
}

function valueLowercase(?UnitEnum $enum): string|int|null
{
    return vl($enum);
}
