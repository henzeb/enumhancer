<?php

namespace Henzeb\Enumhancer\Helpers\Subset;

use Closure;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumLabels;
use Henzeb\Enumhancer\Helpers\EnumValue;
use UnitEnum;

/**
 * @template T of UnitEnum
 */
class EnumSubsetMethods
{
    /**
     * @var T[]
     */
    private array $enums;

    /**
     * @param class-string<T> $enumType
     * @param T ...$enums
     */
    public function __construct(private readonly string $enumType, UnitEnum ...$enums)
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


    /**
     * @return string[]
     */
    public function names(): array
    {
        return array_map(fn(UnitEnum $enum) => $enum->name, $this->enums);
    }

    /**
     * @return string[]|int[]
     */
    public function values(?bool $keepEnumCase = null): array
    {
        return array_map(
            function (mixed $enum) use ($keepEnumCase) {
                return EnumValue::value($enum, $keepEnumCase);
            },
            $this->enums
        );
    }

    /**
     * @param bool|null $keepEnumCase
     * @return array<string|int,string>
     */
    public function dropdown(?bool $keepEnumCase = null): array
    {
        return array_replace(
            [],
            ...array_map(
                function (UnitEnum $case) use ($keepEnumCase) {

                    return [
                        EnumValue::value($case, $keepEnumCase)
                        =>
                            EnumLabels::getLabelOrName($case)
                    ];
                },
                $this->enums
            )
        );
    }

    /**
     * @return T[]
     */
    public function cases(): array
    {
        return $this->enums;
    }
}
