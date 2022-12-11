<?php

namespace Henzeb\Enumhancer\Exceptions;

class ReservedPropertyNameException extends EnumException
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('`%s` is a reserved word and can not be used as property name', $name)
        );
    }
}
