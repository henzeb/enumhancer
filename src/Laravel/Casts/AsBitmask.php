<?php

namespace Henzeb\Enumhancer\Laravel\Casts;

use BackedEnum;
use Henzeb\Enumhancer\Concerns\Bitmasks;
use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;


class AsBitmask implements CastsAttributes
{
    public bool $withoutObjectCaching = true;

    /**
     * @var class-string<Bitmasks>
     */
    protected string $enum;


    public function __construct(string $enum)
    {
        if (!enum_exists($enum)) {
            throw new InvalidArgumentException("Enum class [$enum] does not exist.");
        }

        $this->enum = $enum;
    }


    public function get($model, string $key, mixed $value, array $attributes): Bitmask
    {
        if ($value instanceof Bitmask) {
            return $value;
        }

        return $this->enum::fromMask((int)$value);
    }

    public function set($model, string $key, mixed $value, array $attributes): int
    {
        if (is_array($value)) {
            return $this->enum::mask(...$value)->value();
        }

        if (is_string($value)) {
            $cases = explode(',', $value);
            $cases = array_filter(
                array_map('trim', $cases),
                fn($case) => !empty($case)
            );

            return $this->enum::mask(...$cases)->value();
        }

        if ($value instanceof BackedEnum) {
            return $this->enum::mask($value->name)->value();
        }

        if ($value instanceof Bitmask) {
            return $value->value();
        }


        throw new InvalidArgumentException('The value must be an array of enum cases, string, or single enum case.');
    }

    public function serialize($model, string $key, $value, array $attributes): string
    {
        if ($value instanceof Bitmask) {
            $cases = $value->cases();
            $enabled = [];

            foreach ($cases as $case) {
                $enabled[] = $case->name;
            }


            return implode(',', $enabled);
        }


        return (string)$value;
    }
}
