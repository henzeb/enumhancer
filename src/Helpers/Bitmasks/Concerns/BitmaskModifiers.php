<?php

namespace Henzeb\Enumhancer\Helpers\Bitmasks\Concerns;

use Henzeb\Enumhancer\Helpers\Bitmasks\EnumBitmasks;
use UnitEnum;

trait BitmaskModifiers
{
    public function set(self|UnitEnum|string|int ...$enums): self
    {
        $this->bitmask |= EnumBitmasks::getBits($this->enumFQCN, ...$enums);

        return $this;
    }

    public function unset(self|UnitEnum|string|int ...$enums): self
    {
        $this->bitmask &= ~EnumBitmasks::getBits($this->enumFQCN, ...$enums);

        return $this;
    }

    public function toggle(self|UnitEnum|string|int ...$enums): self
    {
        foreach ($enums as $enum) {
            $this->has($enum) ? $this->unset($enum) : $this->set($enum);
        }

        return $this;
    }

    public function clear(): self
    {
        $this->bitmask = 0;

        return $this;
    }

    public function copy(): self
    {
        return new self(
            $this->forEnum(),
            $this->value()
        );
    }
}
