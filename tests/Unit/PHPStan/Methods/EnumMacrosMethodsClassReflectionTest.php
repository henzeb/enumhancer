<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Methods;

use Closure;
use Henzeb\Enumhancer\PHPStan\Methods\EnumMacrosMethodsClassReflection;
use Henzeb\Enumhancer\PHPStan\Reflections\ClosureMethodReflection;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Macros\MacrosUnitEnum;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Macros\MacrosLogicExceptionEnum;
use LogicException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Adapter\Phpunit\MockeryTestCaseSetUp;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\ClosureTypeFactory;
use PHPStan\Type\Generic\TemplateTypeMap;

class EnumMacrosMethodsClassReflectionTest extends PHPStanTestCase
{
    private EnumMacrosMethodsClassReflection $reflection;
    private ClosureTypeFactory $closureFactory;
    private Closure $aMacroCall;
    private Closure $aStaticMacroCall;
    private Closure $erroneousStaticMacroCall;

    use MockeryPHPUnitIntegration;
    use MockeryTestCaseSetUp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->aMacroCall = function (MacrosUnitEnum $enum): self {
            return $enum;
        };

        $this->aStaticMacroCall = static function (MacrosUnitEnum $enum): self {
            return $enum;
        };

        $this->erroneousStaticMacroCall = static function (
            MacrosLogicExceptionEnum $enum = MacrosLogicExceptionEnum::Test
        ): MacrosLogicExceptionEnum {
            return $enum;
        };

        MacrosUnitEnum::macro(
            'aMacroCall', $this->aMacroCall
        );

        MacrosUnitEnum::macro(
            'aStaticMacroCall', $this->aStaticMacroCall
        );

        $this->closureFactory = self::getContainer()->getByType(ClosureTypeFactory::class);
        $this->reflection = (new EnumMacrosMethodsClassReflection($this->closureFactory));
    }

    public function testShouldReturnFalseWhenNotEnum(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnFalse();

        $this->assertFalse($this->reflection->hasMethod($classReflection, 'method'));

    }

    public function testShouldReturnFalseWhenNotImplementingMacros(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturns(SimpleEnum::class);

        $this->assertFalse($this->reflection->hasMethod($classReflection, 'method'));

    }

    public function testShouldReturnFalseWhenMacroNotSet(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->andReturnTrue();
        $classReflection->expects('getName')->andReturns(MacrosUnitEnum::class);

        $this->assertFalse($this->reflection->hasMethod($classReflection, 'notAMacro'));
    }

    public function testShouldReturnTrueWhenMacroSet(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('isEnum')->twice()->andReturnTrue();
        $classReflection->expects('getName')->twice()->andReturns(MacrosUnitEnum::class);

        $this->assertTrue($this->reflection->hasMethod($classReflection, 'aMacroCall'));
        $this->assertTrue($this->reflection->hasMethod($classReflection, 'aStaticMacroCall'));
    }

    public function testGetMethod(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('getName')->andReturns(MacrosUnitEnum::class);
        $methodReflection = $this->reflection->getMethod($classReflection, 'aMacroCall');

        $this->assertInstanceOf(ClosureMethodReflection::class, $methodReflection);
        $closureType = $this->closureFactory->fromClosureObject($this->aMacroCall);
        $this->assertEquals(
            [
                new FunctionVariant(
                    TemplateTypeMap::createEmpty(),
                    null,
                    $closureType->getParameters(),
                    $closureType->isVariadic(),
                    $closureType->getReturnType()
                ),
            ],
            $methodReflection->getVariants()
        );
        $this->assertFalse($methodReflection->isStatic());
    }

    public function testGetStaticMethod(): void
    {
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('getName')->andReturns(MacrosUnitEnum::class);
        $methodReflection = $this->reflection->getMethod($classReflection, 'aStaticMacroCall');

        $this->assertInstanceOf(ClosureMethodReflection::class, $methodReflection);
        $closureType = $this->closureFactory->fromClosureObject($this->aStaticMacroCall);
        $this->assertEquals(
            [
                new FunctionVariant(
                    TemplateTypeMap::createEmpty(),
                    null,
                    $closureType->getParameters(),
                    $closureType->isVariadic(),
                    $closureType->getReturnType()
                ),
            ],
            $methodReflection->getVariants()
        );
        $this->assertTrue($methodReflection->isStatic());
    }

    public function testThrowsException(): void
    {
        MacrosLogicExceptionEnum::macro('aStaticMacroCall', $this->erroneousStaticMacroCall);
        $classReflection = Mockery::mock(ClassReflection::class);
        $classReflection->expects('getName')->andReturns(MacrosLogicExceptionEnum::class);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'PHPStan Could not properly parse closure `aStaticMacroCall` for '
            . '`Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Macros\MacrosLogicExceptionEnum`, '
            . 'Check if a default value\'s code is not trying to execute this macro.'
        );
        $this->reflection->getMethod($classReflection, 'aStaticMacroCall');

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        MacrosUnitEnum::flushMacros();
        MacrosLogicExceptionEnum::flushMacros();
    }
}
