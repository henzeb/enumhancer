<?php

namespace Henzeb\Enumhancer\Helpers;

use Henzeb\Enumhancer\Exceptions\PropertyAlreadyStoredException;
use Henzeb\Enumhancer\Exceptions\ReservedPropertyNameException;

/**
 * @internal
 */
final class EnumProperties
{
    private static array $reserved = [
        'defaults' => '@default_configure',
        'labels' => '@labels_configure',
        'mapper' => '@mapper_configure',
        'with_mapper' => '@with_mapper',
        'state' => '@state_configure',
        'hooks' => '@state_hook_configure'
    ];

    private static array $global = [];
    protected static array $properties = [];
    protected static array $once = [];

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function store(string $class, string $property, mixed $value, bool $allowReservedWord = false): void
    {
        EnumCheck::check($class);

        self::reservedWordCheck($property, $allowReservedWord);
        self::storedOnceCheck($class, $property);

        self::$properties[$class][$property] = $value;
    }

    /**
     * @throws ReservedPropertyNameException|PropertyAlreadyStoredException
     */
    public static function storeOnce(
        string $class,
        string $property,
        mixed $value,
        bool $allowReservedWord = false
    ): void {
        EnumCheck::check($class);

        self::reservedWordCheck($property, $allowReservedWord);
        self::storedOnceCheck($class, $property);

        self::$once[$class][$property] = $value;
        unset(self::$properties[$class][$property]);
    }

    private static function reservedWordCheck(string $property, bool $allowReservedWord): void
    {
        if (!$allowReservedWord && in_array($property, self::$reserved)) {
            throw new ReservedPropertyNameException($property);
        }
    }

    private static function storedOnceCheck(string $class, string $property): void
    {
        if (isset(self::$once[$class][$property])) {
            throw new PropertyAlreadyStoredException($class, $property);
        }
    }

    public static function reservedWord(string $name): string
    {
        return self::$reserved[$name] ?? $name;
    }

    public static function get(string $class, string $property): mixed
    {
        EnumCheck::check($class);

        return self::$once[$class][$property]
            ?? self::$properties[$class][$property]
            ?? self::$global[$property] ?? null;
    }

    public static function getGlobal(string $property): mixed
    {
        return self::$global[$property] ?? null;
    }

    public static function global(string $property, mixed $value): mixed
    {
        return self::$global[$property] = $value;
    }

    public static function clear(string $class, ?string $property = null): void
    {
        EnumCheck::check($class);

        if (!empty($property)) {
            unset(self::$properties[$class][$property]);
        }

        if (empty($property)) {
            unset(self::$properties[$class]);
        }
    }

    public static function clearGlobal(?string $property = null): void
    {
        if (!empty($property)) {
            unset(self::$global[$property]);
        }

        if (empty($property)) {
            self::$global = [];
        }
    }
}
