<?php

namespace Henzeb\Enumhancer\Exceptions;

use UnitEnum;

class NotAnEnumException extends EnumException
{
    public function __construct(string $actual)
    {
        parent::__construct(
            sprintf('expected `%s` but got `%s`', UnitEnum::class, $actual)
        );
    }
}
