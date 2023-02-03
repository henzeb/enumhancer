<?php

namespace Henzeb\Enumhancer\Helpers;

use Stringable;
use UnitEnum;

class EnumProxy implements Stringable
{
    public readonly string $name;
    public readonly string $value;

    public function __construct(UnitEnum $enum, bool $keepValueCase)
    {
        $this->name = $enum->name;
        $this->value = (string)EnumValue::value($enum, $keepValueCase);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
