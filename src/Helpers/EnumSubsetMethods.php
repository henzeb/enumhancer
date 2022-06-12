<?php

namespace Henzeb\Enumhancer\Helpers;

use Closure;
use UnitEnum;
use BackedEnum;
use Henzeb\Enumhancer\Contracts\EnumSubset;

class EnumSubsetMethods implements EnumSubset
{
    private array $enums;

    public function __construct(private string $enumType, UnitEnum|BackedEnum ...$enums)
    {
        EnumCheck::matches($enumType, ...$enums);

        $this->enums = $enums;
    }

    public function do(Closure $callable): void
    {
        foreach ($this->enums as $enum) {
            $callable($enum);
        }
    }

    public function equals(UnitEnum|string|int ...$equals): bool
    {
        EnumCheck::matches($this->enumType, ...$equals);

        foreach ($this->enums as $enum) {

            if ($this->compare($enum, ...$equals)) {
                return true;
            }
        }

        return false;
    }

    private function compare(UnitEnum $enum, UnitEnum|string|int ...$equals): bool
    {
        foreach ($equals as $equal) {

            if (!$equal instanceof UnitEnum) {
                $equal = EnumMakers::tryMake($enum::class, $equal, true);
            }

            if ($enum === $equal) {
                return true;
            }
        }
        return false;
    }

    public function names(): array
    {
        return array_map(fn(UnitEnum $enum) => $enum->name, $this->enums);
    }

    public function values(): array
    {
        return array_map(
            fn(mixed $enum) => (
                $enum->value
                ?? (method_exists($enum, 'value') ? $enum->value() : null)
                ?? $enum->name
            ), $this->enums
        );
    }

    public function cases(): array
    {
        return $this->enums;
    }
}
