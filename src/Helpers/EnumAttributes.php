<?php

namespace Henzeb\Enumhancer\Helpers;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use UnitEnum;

final class EnumAttributes
{
    public static function fromCase(string $enumClass, UnitEnum $case, string $attributeClass): mixed
    {
        EnumCheck::check($case, $enumClass);

        $enumAttributes = (new ReflectionClassConstant($enumClass, $case->name))
            ->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        if (count($enumAttributes) > 0) {
            return $enumAttributes[0]->newInstance();
        }

        return null;
    }

    public static function fromCaseArray(string $enumClass, UnitEnum $case, string|null $attributeClass = null): array
    {
        EnumCheck::check($case, $enumClass);

        $enumAttributes = (new ReflectionClassConstant($enumClass, $case->name))
            ->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        return array_map(
            fn($enumAttribute) => $enumAttribute->newInstance(),
            $enumAttributes
        );
    }

    public static function fromEnum(string $enumClass, string $attributeClass): mixed
    {
        EnumCheck::check($enumClass);

        $enumAttributes = (new ReflectionClass($enumClass))
            ->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        if (count($enumAttributes) > 0) {
            return $enumAttributes[0]->newInstance();
        }

        return null;
    }

    public static function fromEnumArray(string $enumClass, string|null $attributeClass = null): array
    {
        EnumCheck::check($enumClass);

        $enumAttributes = (new ReflectionClass($enumClass))
            ->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        return array_map(
            fn($enumAttribute) => $enumAttribute->newInstance(),
            $enumAttributes
        );
    }
}
