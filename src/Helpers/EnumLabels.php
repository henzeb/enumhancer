<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Concerns\Labels;
use UnitEnum;

final class EnumLabels
{

    public static function getConfiguredLabels(string $enum): array
    {
        EnumCheck::check($enum);

        return EnumProperties::get(
            $enum,
            EnumProperties::reservedWord('labels')
        ) ?? [];
    }

    public static function getLabels(string $enum): array
    {
        if (EnumImplements::labels($enum)) {
            $configured = self::getConfiguredLabels($enum);
            if (!empty($configured)) {
                return $configured;
            }

            /**
             * @var $enum string|Labels
             */
            return $enum::labels();
        }
        return [];
    }

    public static function getLabel(
        UnitEnum $enum
    ): ?string {
        return self::getLabels($enum::class)[$enum->name]
            ?? self::getLabels($enum::class)[EnumValue::key($enum)]
            ?? (method_exists($enum, 'value') ? $enum->value() : null)
            ?? $enum->value
            ?? $enum->name;
    }

    public static function getLabelOrName(UnitEnum $enum): string
    {
        return self::getLabels($enum::class)[$enum->name] ?? $enum->name;
    }
}
