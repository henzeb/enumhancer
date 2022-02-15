<?php

namespace Henzeb\Enumhancer\Contracts;

use UnitEnum;

abstract class Mapper
{
    abstract protected function mappable(): array;

    private function parse(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof UnitEnum) {
            return $value->name;
        }

        if (!is_string($value)) {
            return null;
        }

        return $value;
    }

    public function map(string $key, string $prefix = null): ?string
    {
        return $this->parse(
            $this->mappable()[$prefix][$key] ??
            $this->mappable()[$key] ?? null);
    }

    public function defined(string $key, string $prefix = null): bool
    {
        return array_key_exists(
            $key,
            $this->mappable()[$prefix]
            ?? $this->mappable()
        );
    }
}
