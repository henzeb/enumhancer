<?php

namespace Henzeb\Enumhancer\Helpers;

use BackedEnum;
use Closure;
use Henzeb\Enumhancer\Concerns\Labels;
use Henzeb\Enumhancer\Contracts\EnumSubset;
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
        EnumCheck::matches($this->enumType, ...$equals);

        foreach ($this->enums as $enum) {
            if ($this->compare($enum, ...$equals)) {
                return true;
            }
        }

        return false;
    }

    private function compare(UnitEnum $enum, UnitEnum|string|int|null ...$equals): bool
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
            function (mixed $enum) {
                return $enum->value
                    ?? (method_exists($enum, 'value') ? $enum->value() : null)
                    ?? $enum->name;
            },
            $this->enums
        );
    }

    public function dropdown(bool $keepEnumCase = false): array
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
