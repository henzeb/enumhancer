<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use PHPUnit\Framework\TestCase;


class SubsetTest extends TestCase
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

    public function testWithoutAll(): void
    {
        $this->assertEquals(
            [],
            EnhancedBackedEnum::without(
                ...EnhancedBackedEnum::cases()
            )->cases()
        );
    }

    public function testWithoutSingleCase(): void
    {
        $this->assertEquals(
            [
                EnhancedBackedEnum::ENUM,
                EnhancedBackedEnum::ANOTHER_ENUM,
                EnhancedBackedEnum::ENUM_3
            ],
            EnhancedBackedEnum::without(EnhancedBackedEnum::WITH_CAPITALS)->cases()
        );
    }

    public function testWithoutMultipleCase(): void
    {
        $this->assertEquals(
            [
                EnhancedBackedEnum::ENUM_3,
                EnhancedBackedEnum::WITH_CAPITALS,
            ],
            EnhancedBackedEnum::without(
                EnhancedBackedEnum::ANOTHER_ENUM,
                EnhancedBackedEnum::ENUM,
            )->cases()
        );
    }
}
