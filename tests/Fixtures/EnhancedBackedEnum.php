<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Constructor;
use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Contracts\Mapper;
use function Henzeb\Enumhancer\Functions\n;


/**
 * @method static self anotherMappedEnum()
 * @method static bool isMapped()
 */
enum EnhancedBackedEnum: string
{
    use Enhancers, Constructor;

    case ENUM = 'an enum';
    case ANOTHER_ENUM = 'another enum';
    case ENUM_3 = 'third_enum';
    case WITH_CAPITALS = 'THIRD enum';

    const ConstantEnum = self::ENUM_3;

    private const MAP_CONSTANT = [
        'expected' => self::WITH_CAPITALS
    ];

    public const MAP_CONSTANT_2 = [
        'expected2' => self::ConstantEnum
    ];


    public static function labels(): array
    {
        return [
            n(self::ENUM) => 'My label'
        ];
    }

    protected static function mapper(): ?Mapper
    {
        return new class extends Mapper {
            protected function mappable(): array
            {
                return [
                    'anotherMappedEnum' => EnhancedBackedEnum::ENUM,
                    EnhancedUnitEnum::Mapped->name => EnhancedBackedEnum::ANOTHER_ENUM
                ];
            }
        };
    }
}
