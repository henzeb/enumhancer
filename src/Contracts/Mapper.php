<?php

namespace Henzeb\Enumhancer\Contracts;

use UnitEnum;

abstract class Mapper
{
    abstract protected function mappable(): array;

    private function parse(mixed $value): ?string
    {
        if (null === $value) {
            $value = null;
        }

        if (empty($value)) {
            $value = null;
        }

        if ($value instanceof UnitEnum) {
            $value = $value->name;
        }

        if (!is_string($value)) {
            $value = null;
        }

        return $value;
    }

    public function map(string $key, string $prefix = null): ?string
    {
        return $this->parse(
            $this->mappable()[$prefix][$key] ??
            $this->mappable()[$prefix][strtolower($key)] ??
            $this->mappable()[$key] ??
            $this->mappable()[strtolower($key)] ?? null
        );
    }

    public function defined(string $key, string $prefix = null): bool
    {
        return (bool)$this->map($key, $prefix);
    }

    public function keys(string $prefix = null): array
    {
        $mappable = $this->mappable();

        return array_merge(
            array_keys(

                array_filter(
                    $mappable,
                    function ($value) {
                        return !is_array($value);
                    }
                ),
            ),
            is_array($mappable[$prefix] ?? null) ? array_keys($mappable[$prefix]) : []
        );
    }
}
