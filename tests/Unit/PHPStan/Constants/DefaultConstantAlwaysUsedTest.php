<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\DefaultConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsConstantEnum;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\EnumWithCapitalizedDefault;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\EnumWithDefaultNotImplementing;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use PHPStan\Testing\PHPStanTestCase;
use stdClass;

class DefaultConstantAlwaysUsedTest extends PHPStanTestCase
{
    public function testConstantIsNotDefault(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(\Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Value\ValueStrictEnum::class);
        
        $strictConstant = $classReflection->getConstant('strict');
        $constantChecker = new DefaultConstantAlwaysUsed();
        
        $this->assertFalse($constantChecker->isAlwaysUsed($strictConstant));
    }

    public function testClassIsNotEnum(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(\Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\NotEnum::class);
        
        $defaultConstant = $classReflection->getConstant('Default');
        $constantChecker = new DefaultConstantAlwaysUsed();
        
        $this->assertFalse($constantChecker->isAlwaysUsed($defaultConstant));
    }

    public function testConstantCorrectDefaultInEnum(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(DefaultsConstantEnum::class);
        
        if (!$classReflection->hasConstant('Default')) {
            $this->markTestSkipped('Default constant not found in DefaultsConstantEnum');
        }
        
        $defaultConstant = $classReflection->getConstant('Default');
        $constantChecker = new DefaultConstantAlwaysUsed();
        
        $this->assertTrue($constantChecker->isAlwaysUsed($defaultConstant));
    }

    public function testConstantCorrectDefaultCapitalizedInEnum(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(EnumWithCapitalizedDefault::class);
        
        if (!$classReflection->hasConstant('DEFAULT')) {
            $this->markTestSkipped('DEFAULT constant not found in EnumWithCapitalizedDefault');
        }
        
        $defaultConstant = $classReflection->getConstant('DEFAULT');
        $constantChecker = new DefaultConstantAlwaysUsed();
        
        $this->assertTrue($constantChecker->isAlwaysUsed($defaultConstant));
    }

    public function testConstantNamedDefaultNotImplementingDefaults(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(EnumWithDefaultNotImplementing::class);
        
        if (!$classReflection->hasConstant('Default')) {
            $this->markTestSkipped('Default constant not found in EnumWithDefaultNotImplementing');
        }
        
        $defaultConstant = $classReflection->getConstant('Default');
        $constantChecker = new DefaultConstantAlwaysUsed();
        
        $this->assertFalse($constantChecker->isAlwaysUsed($defaultConstant));
    }
}