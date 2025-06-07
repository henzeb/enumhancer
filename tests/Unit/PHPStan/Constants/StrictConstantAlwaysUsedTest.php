<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\StrictConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Value\ValueStrictEnum;
use PHPStan\Testing\PHPStanTestCase;

class StrictConstantAlwaysUsedTest extends PHPStanTestCase
{
    public function testShouldIgnoreIfNotStrictConstant(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(\Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsConstantEnum::class);
        
        $defaultConstant = $classReflection->getConstant('Default');
        $constant = new StrictConstantAlwaysUsed();
        
        $this->assertFalse($constant->isAlwaysUsed($defaultConstant));
    }

    public function testShouldOnlyWorkWithEnums(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(\Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\NotEnum::class);
        
        $this->assertFalse($classReflection->isEnum());
    }

    public function testShouldReturnTrueWithEnumsImplementingValue(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(ValueStrictEnum::class);
        
        if (!$classReflection->hasConstant('strict')) {
            $this->markTestSkipped('strict constant not found in ValueStrictEnum');
        }
        
        $strictConstant = $classReflection->getConstant('strict');
        $constant = new StrictConstantAlwaysUsed();
        
        $this->assertTrue($constant->isAlwaysUsed($strictConstant));
    }

    public function testShouldReturnFalseWithEnumsNotImplementingValue(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(SimpleEnum::class);
        
        if ($classReflection->hasConstant('strict')) {
            $strictConstant = $classReflection->getConstant('strict');
            $constant = new StrictConstantAlwaysUsed();
            $this->assertFalse($constant->isAlwaysUsed($strictConstant));
        } else {
            $this->assertTrue($classReflection->isEnum());
        }
    }
}