<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;
use BadMethodCallException;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumCompare;
use Henzeb\Enumhancer\Helpers\EnumSubsetMethods;

trait Comparison
{
    /**
     * @mixin BackedEnum
     */
    final public function equals(self|string|int ...$equals): bool
    {
        return EnumCompare::equals($this, ...$equals);
    }

    final public function __call(string $name, array $arguments): self|bool
    {
        $nameIsEnum = !EnumMakers::tryMake(self::class, $name, true);
        if (((!str_starts_with($name, 'is') && !str_starts_with($name, 'isNot')) || count($arguments)) && $nameIsEnum) {
            throw new BadMethodCallException(sprintf('Call to undefined method %s::%s(...)', $this::class, $name));
        }

        if (!$nameIsEnum) {
            return self::__callStatic($name, []);
        }

        $value = substr($name, str_starts_with($name, 'isNot') ? 5 : 2);

        if (!EnumMakers::tryMake(self::class, $value, true)) {
            throw new BadMethodCallException(sprintf('Call to undefined method %s::%s(...)', $this::class, $name));
        }
        if (str_starts_with($name, 'isNot')) {
            return !$this->equals($value);
        }
        return $this->equals($value);
    }

}
