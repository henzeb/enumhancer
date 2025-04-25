<?php

namespace Henzeb\Enumhancer\Contracts;

use UnitEnum;
use function strtolower;
use function trigger_error;

/**
 * @method static string|null map(string|UnitEnum $key, string $prefix = null)
 * @method static string|null defined(string|UnitEnum $key, string $prefix = null)
 * @method static static flip(string $prefix = null)
 * @method static array keys(string $prefix = null)
 */
abstract class Mapper
{
    private bool $flip = false;

    private ?array $flipped = null;
    private ?string $flipPrefix = null;

    /**
     * @return array<string|int,string|int|UnitEnum|array<string|int,string|int|UnitEnum>>
     */
    abstract protected function mappable(): array;

    public function makeFlipped(?string $prefix = null): self
    {
        $this->flip = true;
        $this->flipped = null;
        $this->flipPrefix = $prefix;

        return $this;
    }

    private function flipMethod(?string $prefix = null): self
    {
        return (clone $this)->makeFlipped($prefix);
    }

    private function parseValue(mixed $value): string|int|null
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

        if (!is_string($value) && !is_int($value)) {
            $value = null;
        }

        return $value;
    }

    private function getMapWithPrefix(?string $prefix = null): array
    {
        /**
         * @var array $mappable
         */
        $mappable = $this->mappable();
        return array_change_key_case($mappable[$prefix] ?? []);
    }

    private function getMap(?string $prefix = null): array
    {
        if ($this->flip) {
            return $this->flipped ?? $this->flipped = $this->flipMappable($prefix);
        }

        return array_change_key_case($this->mappable());
    }

    private function mapMethod(string|UnitEnum $key, ?string $prefix = null): string|int|null
    {
        $key = $this->parseValue($key);

        if (is_string($key)) {
            $key = strtolower($key);
        }

        return $this->parseValue(
            ($this->flip ? null : $this->getMapWithPrefix($prefix)[$key] ?? null)
            ??
            $this->getMap($prefix)[$key]
            ?? null
        );
    }

    private function definedMethod(string|UnitEnum $key, ?string $prefix = null): bool
    {
        return (bool)$this->map($key, $prefix);
    }

    private function keysMethod(?string $prefix = null): array
    {
        if (!$prefix || $this->flip) {
            $mappable = $this->getMap($prefix);
        }

        if (!isset($mappable)) {
            $mappable = [...$this->getMap(), ...$this->getMapWithPrefix($prefix)];
        }

        return array_unique(
            array_merge(
                array_keys(
                    array_filter(
                        $mappable,
                        function ($value) {
                            return !is_array($value);
                        }
                    ),
                ),
                is_array($mappable[$prefix] ?? null) ? array_keys($mappable[$prefix]) : []
            )
        );
    }

    private function flipMappable(?string $prefix = null): array
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

    public function __call(string $name, array $arguments): mixed
    {
        return match ($name) {
            'map' => $this->mapMethod(...$arguments),
            'defined' => $this->definedMethod(...$arguments),
            'keys' => $this->keysMethod(...$arguments),
            'flip' => $this->flipMethod(...$arguments),
            default => $this->triggerError($name)
        };
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        return self::newInstance()->$name(...$arguments);
    }

    private function triggerError(string $name): never
    {
        throw new \Error(sprintf(
            'Uncaught Error: Call to undefined method %s::%s()',
            static::class,
            $name
        ));
    }

    public static function newInstance(mixed ...$parameters): static
    {
        return new static(...$parameters);
    }
}
