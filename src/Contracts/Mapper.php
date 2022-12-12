<?php

namespace Henzeb\Enumhancer\Contracts;

use UnitEnum;

abstract class Mapper
{
    private bool $flip = false;

    private ?array $flipped = null;
    private ?string $flipPrefix = null;

    abstract protected function mappable(): array;

    public function makeFlipped(string $prefix = null): self
    {
        $this->flip = true;
        $this->flipped = null;
        $this->flipPrefix = $prefix;

        return $this;
    }

    public static function flip(string $prefix = null): self
    {
        return (new static())->makeFlipped($prefix);
    }

    private function parseValue(mixed $value): ?string
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

    private function getMapWithPrefix(string $prefix = null): array
    {
        return array_change_key_case($this->mappable()[$prefix] ?? []);
    }

    private function getMap(string $prefix = null): array
    {
        if ($this->flip) {
            return $this->flipped ?? $this->flipped = $this->flipMappable($prefix);
        }

        return array_change_key_case($this->mappable());
    }

    public function map(string|UnitEnum $key, string $prefix = null): ?string
    {
        $key = strtolower($this->parseValue($key));

        return $this->parseValue(
            $this->getMapWithPrefix($prefix)[$key]
            ??
            $this->getMap()[$key]
            ?? null
        );
    }

    public function defined(string|UnitEnum $key, string $prefix = null): bool
    {
        return (bool)$this->map($key, $prefix);
    }

    public function keys(string $prefix = null): array
    {
        $mappable = $this->getMap($prefix);

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

    private function flipMappable(string $prefix = null): array
    {
        return array_change_key_case(
            array_flip(
                array_filter(
                    array_map(
                        fn($value) => is_array($value) ? null : $this->parseValue($value),
                        $this->getMapWithPrefix($prefix ?? $this->flipPrefix) ?: $this->mappable()
                    )
                )
            )
        );
    }
}
