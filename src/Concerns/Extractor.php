<?php

namespace Henzeb\Enumhancer\Concerns;

use Henzeb\Enumhancer\Helpers\EnumExtractor;

trait Extractor
{
    public static function extract(string $text): array
    {
        return EnumExtractor::extract(self::class, $text);
    }
}
