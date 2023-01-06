<?php

namespace Henzeb\Enumhancer\Helpers\Bitmasks;

use BackedEnum;
use Henzeb\Enumhancer\Exceptions\InvalidBitmaskEnum;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\EnumDefaults;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumLabels;
use Henzeb\Enumhancer\Helpers\EnumValue;
use ReflectionClass;
use UnitEnum;
use const E_USER_ERROR;

final class EnumBitmasks
{
    private static $isValid = [];

    private static function countSetBits(int $bit): int
    {
        if ($bit <= 0) {
            return 0;
        }

        return ($bit & 1) +
            self::countSetBits($bit >> 1);
    }

    public static function isBit(mixed $bit): bool
    {
        return self::isInt($bit) && self::countSetBits($bit) === 1;
    }

    public static function validateBitmaskCases(string $enum): void
    {
        EnumCheck::check($enum);

        if (in_array($enum, self::$isValid)
            || !is_a($enum, BackedEnum::class, true)
            || self::ignoreIntValues($enum)
        ) {
            self::$isValid[] = $enum;
            self::$isValid = array_unique(self::$isValid);
            return;
        }

        self::validateBitCases($enum);

        self::$isValid[] = $enum;
    }

    public static function ignoreIntValues(string $enum): bool
    {
        /**
         * @var UnitEnum $enum
         */

        EnumCheck::check($enum);

        foreach ((new ReflectionClass($enum))->getConstants() as $constant => $value) {
            if (strtolower($constant) === 'bit_values' and is_bool($value)) {
                return !$value;
            }
        }
        return true;
    }

    private static function validateBitCases(BackedEnum|string $enum): void
    {
        foreach ($enum::cases() as $case) {
            self::validateBitCase($case);
        }
    }

    private static function validateBitCase(BackedEnum $case): void
    {
        if (self::isInt($case->value) && !self::isBit($case->value)) {
            self::triggerInvalidBitCase($case::class, $case);
        }
    }

    public static function getBit(UnitEnum $enum): int
    {
        self::validateBitmaskCases($enum::class);

        $value = EnumValue::value($enum);

        if (self::ignoreIntValues($enum::class)
            || !filter_var($value, FILTER_VALIDATE_INT)
        ) {
            return pow(
                2,
                (int)array_search($enum, $enum::cases())
            );
        }

        return (int)$value;
    }

    public static function getMask(string $class, UnitEnum|string|int ...$enums): Bitmask
    {
        return new Bitmask(
            $class,
            self::getBits($class, ...$enums)
        );
    }

    public static function getBits(string|UnitEnum $class, Bitmask|UnitEnum|string|int ...$values): int
    {
        $bits = 0;

        foreach ($values as $value) {
            $bits |= self::castToBits($value, $class);
        }

        return $bits;
    }

    private static function castToBits(Bitmask|UnitEnum|string|int $value, string|UnitEnum $class): int
    {
        $class = \is_object($class) ? $class::class : $class;

        if ($value instanceof Bitmask) {
            self::forOrFail($class, $value);
            return $value->value();
        }

        $enum = EnumGetters::tryGet($class, $value);

        if ($enum) {
            return self::getBit($enum);
        }

        if (self::isInt($value) && self::isValidBitmask($class, $value)) {
            /**
             * @var int $value
             */
            return $value;
        }

        self::throwMismatch($class, gettype($value));
    }

    public static function getCaseBits(string $class): array
    {
        /**
         * @var UnitEnum|string $class
         */
        $bits = [];

        foreach ($class::cases() as $bit) {
            $bits[self::getBit($bit)] = EnumLabels::getLabelOrName($bit);
        }

        return $bits;
    }

    public static function fromMask(string $enum, int $mask): Bitmask
    {
        /**
         * @var $enum UnitEnum|string
         */

        return new Bitmask(
            $enum,
            $mask
        );
    }

    public static function tryMask(string $enum, ?int $mask, Bitmask|UnitEnum|string|int|null ...$enums): Bitmask
    {
        /**
         * @var $enum UnitEnum|string
         */

        if (!is_null($mask) && self::isValidBitmask($enum, $mask)) {
            return new Bitmask($enum, $mask);
        }

        return new Bitmask(
            $enum,
            self::getBits(
                $enum,
                ...array_filter(
                    $enums ?: [EnumDefaults::default($enum)]
                )
            ),
        );
    }

    public static function validateBitmaskOrThrowException(UnitEnum|string $enum, int $bitmask): void
    {
        if (!self::isValidBitmask($enum, $bitmask)) {
            throw new InvalidBitmaskEnum(
                is_object($enum) ? $enum::class : $enum,
                $bitmask
            );
        }
    }

    public static function isValidBitmask(UnitEnum|string $enum, mixed $bitmask): bool
    {
        if (!self::isInt($bitmask)) {
            return false;
        }

        $maxbits = self::getBits($enum, ...$enum::cases());

        if ($maxbits < $bitmask) {
            return false;
        }

        return (int)$bitmask === ($maxbits & $bitmask);
    }

    private static function forOrFail(string $class, Bitmask $enum): void
    {
        if (!$enum->for($class)) {
            self::throwMismatch(
                $class,
                $enum->forEnum()
            );
        }
    }

    public static function throwMismatch(string $expected, string $given): never
    {
        EnumCheck::check($expected);
        EnumCheck::check($given);

        throw new InvalidBitmaskEnum(
            $expected,
            $given
        );
    }

    protected static function triggerInvalidBitCase(UnitEnum|string $enum, UnitEnum $case): never
    {
        trigger_error(
            sprintf('%s::%s is not a valid bit value', $enum::class ?? $enum, $case->name),
            E_USER_ERROR
        );
    }

    protected static function isInt(mixed $value): bool
    {
        return is_scalar($value) && filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public static function triggerNotImplementingBitmasks(string $enum): never
    {
        trigger_error(
            sprintf('`%s` is not implementing `Bitmasks`', $enum),
            E_USER_ERROR
        );
    }
}
