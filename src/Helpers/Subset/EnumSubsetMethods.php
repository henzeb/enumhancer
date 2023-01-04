<?php

namespace Henzeb\Enumhancer\Helpers\Subset;

use BackedEnum;
use Closure;
use Henzeb\Enumhancer\Concerns\Labels;
use Henzeb\Enumhancer\Contracts\EnumSubset;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Helpers\EnumValue;
use UnitEnum;

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

    public function equals(UnitEnum|string|int|null ...$equals): bool
    {
        foreach ($this->enums as $enum) {
            if ($this->compare($enum, ...$equals)) {
                return true;
            }
        }

        return false;
    }

    private function compare(UnitEnum $enum, UnitEnum|string|int|null ...$equals): bool
    {
        $result = false;
        foreach ($equals as $equal) {
            $equal = $this->asEnumObject($equal);

            EnumCheck::matches($this->enumType, $equal);

            if ($enum === $equal) {
                $result = true;
            }
        }
        return $result;
    }

    private function asEnumObject(mixed $value): ?UnitEnum
    {
        if (!$value instanceof UnitEnum || $this->enumType !== $value::class) {
            return EnumGetters::tryGet($this->enumType, $value, true);
        }

        return $value;
    }

    public function names(): array
    {
        return array_map(fn(UnitEnum $enum) => $enum->name, $this->enums);
    }

    public function values(): array
    {
        return array_map(
            function (mixed $enum) {
                return $enum->value
                    ?? (method_exists($enum, 'value') ? $enum->value() : null)
                    ?? $enum->name;
            },
            $this->enums
        );
    }

    public function dropdown(bool $keepEnumCase = null): array
    {
        return array_replace(
            [],
            ...array_map(
                function (UnitEnum $case) use ($keepEnumCase) {
                    /**
                     * @var $case UnitEnum|Labels
                     */
                    return [
                        EnumValue::value($case, $keepEnumCase)
                        =>
                            EnumImplements::labels($case::class) ? $case->label() : $case->name
                    ];
                },
                $this->enums
            )
        );
    }

    public function cases(): array
    {
        return $this->enums;
    }
}
