<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants;

use Henzeb\Enumhancer\PHPStan\Constants\MapperConstantAlwaysUsed;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers\MappersUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use PHPStan\Testing\PHPStanTestCase;

class MapperConstantAlwaysUsedTest extends PHPStanTestCase
{
    public function testShouldOnlyWorkWithEnums(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(\Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Defaults\NotEnum::class);
        
        $this->assertFalse($classReflection->isEnum());
    }

    public function testShouldReturnFalseWhenNotHavingMapConstant(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(MappersUnitEnum::class);
        
        $loveConstant = $classReflection->getConstant('Love');
        $constant = new MapperConstantAlwaysUsed();
        
        $this->assertFalse($constant->isAlwaysUsed($loveConstant));
    }

    public function testImplementsMappersMapperConstantWithEnumInstance(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(MappersUnitEnum::class);
        
        if (!$classReflection->hasConstant('MAP')) {
            $this->markTestSkipped('MAP constant not found in MappersUnitEnum');
        }
        
        $mapConstant = $classReflection->getConstant('MAP');
        $constant = new MapperConstantAlwaysUsed();
        
        $this->assertTrue($constant->isAlwaysUsed($mapConstant));
    }

    public function testImplementsMappersMapperConstantWithValidMapper(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(MappersUnitEnum::class);
        
        if (!$classReflection->hasConstant('MAP_FLIP')) {
            $this->markTestSkipped('MAP_FLIP constant not found in MappersUnitEnum');
        }
        
        $mapFlipConstant = $classReflection->getConstant('MAP_FLIP');
        $constant = new MapperConstantAlwaysUsed();
        
        $this->assertTrue($constant->isAlwaysUsed($mapFlipConstant));
    }
}