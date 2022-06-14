<?php

namespace Henzeb\Enumhancer\Exceptions;

use Exception;

abstract class EnumException extends Exception
{
    /**
     * @throws EnumException|Exception
     */
    public static function throw(mixed ...$parameters): never
    {
        throw new static(...$parameters);
    }
}
