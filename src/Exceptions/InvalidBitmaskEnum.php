<?php

namespace Henzeb\Enumhancer\Exceptions;

class InvalidBitmaskEnum extends EnumException
{
    public function __construct(string $expected, string $given)
    {
        parent::__construct(
            sprintf(
                'invalid mask for `%s`. a mask of `%d` given',
                $expected,
                $given
            )
        );
    }
}
