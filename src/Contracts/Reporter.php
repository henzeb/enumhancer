<?php
namespace Henzeb\Enumhancer\Contracts;

use BackedEnum;

interface Reporter
{
    public function report(string $enum, string $key, ?BackedEnum $context): void;
}
