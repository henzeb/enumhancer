<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumAttributes;

trait Attributes
{
    protected function getAttribute(string $attributeClass): mixed
    {
        return EnumAttributes::fromCase(self::class, $this, $attributeClass);
    }

    protected function getAttributes(string $attributeClass = null): array
    {
        return EnumAttributes::fromCaseArray(self::class, $this, $attributeClass);
    }

    protected static function getEnumAttribute(string $attributeClass): mixed
    {
        return EnumAttributes::fromEnum(self::class, $attributeClass);
    }

    protected static function getEnumAttributes(string $attributeClass = null): array
    {
        return EnumAttributes::fromEnumArray(self::class, $attributeClass);
    }
}
