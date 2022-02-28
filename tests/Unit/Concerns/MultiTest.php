<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\Comparison;
use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;


class MultiTest extends TestCase
{
    public function testOfSubsetShouldEquals(): void
    {
        $this->assertTrue(EnhancedBackedEnum::of(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::ANOTHER_ENUM
        )->equals(
            EnhancedBackedEnum::ANOTHER_ENUM
        ));
    }

    public function testOfSubsetShouldNotEquals(): void
    {
        $this->assertFalse(EnhancedBackedEnum::of(
            EnhancedBackedEnum::ENUM,
            EnhancedBackedEnum::ANOTHER_ENUM
        )->equals(
            EnhancedBackedEnum::ENUM_3
        ));
    }

    public function testOfSubsetShouldUseAllCases(): void
    {
        $this->assertTrue(EnhancedBackedEnum::of()->equals(
            EnhancedBackedEnum::ANOTHER_ENUM
        ));
    }

    public function testOfSubsetShouldUseAllCasesEqualsString(): void
    {
        $this->assertTrue(EnhancedBackedEnum::of()->equals(
            'ANOTHER_ENUM'
        ));
    }

    public function testOfSubsetShouldUseAllCasesENotqualsString(): void
    {
        $this->assertFalse(EnhancedBackedEnum::of()->equals(
            'DOESNOTEXIST'
        ));
    }
}
