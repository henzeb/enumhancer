<?php

namespace Henzeb\Enumhancer\Exceptions;

class SyntaxException extends EnumException
{
    public function __construct(string $expected, mixed $actual)
    {
        parent::__construct(
            sprintf(
                'syntax error, unexpected \'return\' (T_RETURN), expecting \'%s\' or \'%s\'',
                $expected,
                gettype($actual)
            )
        );
    }
}
