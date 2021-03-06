<?php

namespace Henzeb\Enumhancer\Concerns;

use BadMethodCallException;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use Henzeb\Enumhancer\Helpers\EnumCompare;

trait Comparison
{
    public function equals(self|string|int ...$equals): bool
    {
        return EnumCompare::equals($this, ...$equals);
    }

    public function __call(string $name, array $arguments): self|bool
    {
        if (EnumCompare::isValidCall(self::class, $name, $arguments)) {
            throw new BadMethodCallException(sprintf('Call to undefined method %s::%s(...)', $this::class, $name));
        }

        $nameIsEnum = !EnumMakers::tryMake(self::class, $name, true);

        if (!$nameIsEnum && method_exists(self::class, '__callStatic')) {
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
