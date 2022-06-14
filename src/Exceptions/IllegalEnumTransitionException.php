<?php

namespace Henzeb\Enumhancer\Exceptions;

class IllegalEnumTransitionException extends EnumException
{
    public function __construct(\UnitEnum $from, \UnitEnum $to)
    {
        parent::__construct(
            sprintf('Transition from `%s` to `%s` is not allowed!', $from->name, $to->name)
        );
    }
}
