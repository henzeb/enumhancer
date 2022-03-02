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
        foreach($this->enums as $enum) {
            $callable($enum);
        }
    }

    public function equals(string|UnitEnum|BackedEnum ...$equals): bool
    {
        EnumCheck::matches($this->enumType, ...$equals);

        foreach ($this->enums as $enum) {

            if ($this->compare($enum, ...$equals)) {
                return true;
            }
        }

        return false;
    }

    private function compare(UnitEnum|BackedEnum $enum, string|UnitEnum|BackedEnum ...$equals): bool
    {
        foreach ($equals as $equal) {
            if ($enum->name === $equal) {
                return true;
            }

            if (property_exists($enum, 'value') && $enum->value === $equal) {
                return true;
            }

            if (method_exists($enum, 'value') && $enum->value() === $equal) {
                return true;
            }

            if (property_exists($equal, 'name') && $enum->name === $equal->name) {
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
            fn(mixed $enum) => $enum->value ?? $enum->value(), $this->enums
        );
    }
}
