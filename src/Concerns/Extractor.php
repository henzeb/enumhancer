<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumExtractor;

trait Extractor
{
    final public static function extract(string $text, Mapper|string|null $mapper = null): array
    {
        return EnumExtractor::extract(self::class, $text, $mapper);
    }
}
