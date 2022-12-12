<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

use Henzeb\Enumhancer\Concerns\Enhancers;
use Henzeb\Enumhancer\Concerns\Constructor;

/**
 * @method static self anotherMappedEnum()
 * * @method static self ENUM()
 * @method isAnother_Enum()
 * @method isEnum()
 * @method isNotEnum()
 */
enum EnhancedUnitEnum
{
    use Enhancers, Constructor;

    case ENUM;
    case ANOTHER_ENUM;
    case THIRD_ENUM;

    case Unique;

    case Mapped;

    public function isEnumFunction(): bool
    {
        return $this->equals(self::ENUM());
    }
}
