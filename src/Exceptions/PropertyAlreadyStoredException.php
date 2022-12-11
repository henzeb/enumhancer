<?php

namespace Henzeb\Enumhancer\Exceptions;

class PropertyAlreadyStoredException extends EnumException
{
    public function __construct(string $enum, string $propertyName)
    {
        parent::__construct(
            sprintf('%s already has a property named `%s`', $enum, $propertyName)
        );
    }
}
