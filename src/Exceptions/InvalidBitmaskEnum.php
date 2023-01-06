<?php

namespace Henzeb\Enumhancer\Exceptions;

class InvalidBitmaskEnum extends EnumException
{
    public function __construct(string $expected, int|string $given)
    {
        parent::__construct(
            sprintf(
                'invalid mask for `%s`. `%s` given',
                $expected,
                $given
            )
        );
    }
}
