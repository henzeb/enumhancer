<?php

namespace Henzeb\Enumhancer\Helpers;

use UnitEnum;
use Stringable;
use function Henzeb\Enumhancer\Functions\v;

class EnumProxy implements Stringable
{
    public readonly string $name;
    public readonly string $value;

    public function __construct(private readonly UnitEnum $enum, bool $keepValueCase)
    {
        $this->name = $this->enum->name;
        $this->value = v($this->enum, $keepValueCase);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
