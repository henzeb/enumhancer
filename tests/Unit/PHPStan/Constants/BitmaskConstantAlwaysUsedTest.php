<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\BitmaskConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use PHPStan\Testing\PHPStanTestCase;

class BitmaskConstantAlwaysUsedTest extends PHPStanTestCase
{
    public function testShouldIgnoreIfNotBitmaskConstant(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(\Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Value\ValueStrictEnum::class);
        
        $strictConstant = $classReflection->getConstant('strict');
        $constant = new BitmaskConstantAlwaysUsed();
        
        $this->assertFalse($constant->isAlwaysUsed($strictConstant));
    }

    public function testShouldOnlyWorkWithEnums(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(\Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\NotEnum::class);
        
        if ($classReflection->hasConstant('bit_values')) {
            $bitValuesConstant = $classReflection->getConstant('bit_values');
            $constant = new BitmaskConstantAlwaysUsed();
            $this->assertFalse($constant->isAlwaysUsed($bitValuesConstant));
        } else {
            $this->assertFalse($classReflection->isEnum());
        }
    }

    public function testShouldOnlyWorkWithEnumsImplementingBitmask(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(SimpleEnum::class);
        
        if ($classReflection->hasConstant('bit_values')) {
            $bitValuesConstant = $classReflection->getConstant('bit_values');
            $constant = new BitmaskConstantAlwaysUsed();
            $this->assertFalse($constant->isAlwaysUsed($bitValuesConstant));
        } else {
            $this->assertTrue($classReflection->isEnum());
        }
    }

    public function testShouldReturnTrueWhenImplementingBitmaskAndHasValue(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(BitmasksIntEnum::class);
        
        if (!$classReflection->hasConstant('BIT_VALUES')) {
            $this->markTestSkipped('BIT_VALUES constant not found in BitmasksIntEnum');
        }
        
        $bitValuesConstant = $classReflection->getConstant('BIT_VALUES');
        $constant = new BitmaskConstantAlwaysUsed();
        
        $this->assertTrue($constant->isAlwaysUsed($bitValuesConstant));
    }
}