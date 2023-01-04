<?php

namespace Henzeb\Enumhancer\Helpers\Bitmasks;

use Henzeb\Enumhancer\Helpers\Bitmasks\Concerns\BitmaskModifiers;
use Henzeb\Enumhancer\Helpers\Bitmasks\Concerns\BitmaskValidators;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use UnitEnum;

final class Bitmask
{
    use BitmaskModifiers, BitmaskValidators;

    public function __construct(private readonly string $enumFQCN, private int $bitmask)
    {
        EnumCheck::check($enumFQCN, self::class);
        EnumBitmasks::validateBitmaskOrThrowException($enumFQCN, $this->bitmask);
    }

    public function has(UnitEnum|string|int $enum): bool
    {
        return $this->all($enum);
    }

    public function all(self|UnitEnum|string|int ...$bits): bool
    {
        $mask = EnumBitmasks::getBits($this->enumFQCN, ...$bits);

        if ($mask === 0) {
            return true;
        }

        return ($this->value() & $mask) === $mask;
    }

    public function any(self|UnitEnum|string|int ...$bits): bool
    {
        $mask = EnumBitmasks::getBits($this->enumFQCN, ...$bits);

        if ($mask === 0) {
            return true;
        }

        return ($this->value() & $mask) > 0;
    }

    public function xor(self|UnitEnum|string|int ...$bits): bool
    {
        if (count($bits) === 0) {
            return false;
        }

        $result = false;

        foreach ($bits as $bit) {
            $hasBit = $this->has($bit);

            if ($hasBit && $result) {
                return false;
            }

            if ($hasBit) {
                $result = true;
            }
        }

        return $result;
    }

    public function none(self|UnitEnum|string|int ...$bits): bool
    {
        $mask = EnumBitmasks::getBits($this->enumFQCN, ...$bits);

        if ($mask === 0) {
            return true;
        }

        return ($this->value() & $mask) === 0;
    }

    public function value(): int
    {
        return $this->bitmask;
    }

    public function cases(): array
    {
        return array_values(
            array_filter(
                $this->enumFQCN::cases(),
                fn(UnitEnum $case) => $this->bitmask & EnumBitmasks::getBit($case)
            )
        );
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }
}
