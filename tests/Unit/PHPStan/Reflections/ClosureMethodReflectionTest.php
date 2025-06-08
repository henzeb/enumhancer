<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Reflections;

use Henzeb\Enumhancer\PHPStan\Reflections\ClosureMethodReflection;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Unit\PHPStan\Fixtures\Mappers\MapperClass;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\ShouldNotHappenException;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\ClosureTypeFactory;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPUnit\Framework\Attributes\DataProvider;

class ClosureMethodReflectionTest extends PHPStanTestCase
{
    /**
     * @var mixed|ClosureTypeFactory
     */
    private ClosureTypeFactory $closureFactory;

    protected function setUp(): void
    {
        $this->closureFactory = self::getContainer()->getByType(ClosureTypeFactory::class);
    }

    public static function providesTestcases(): array
    {
        return [
            [
                'method' => 'aMethod',
                'callable' => static fn() => true,
                'isStatic' => true,
                'docComment' => false,
            ],
            [
                'method' => 'anotherMethod',
                'callable' => function (SimpleEnum|null $enum = null): ?SimpleEnum {
                    return $enum;
                },
                'isStatic' => false,
                'docComment' => null,
            ],
            [
                'method' => 'yetAnotherMethod',
                'callable' => function (SimpleEnum|null $enum = null): ?SimpleEnum {
                    return $enum;
                },
                'isStatic' => false,
                'docComment' => true,
            ],
            [
                'method' => 'callMeMaybe',
                'callable' => function (SimpleEnum|null $enum = null): ?SimpleEnum {
                    return $enum;
                },
                'isStatic' => false,
                'docComment' => '/** docblock */',
            ]
        ];
    }

    /**
     * @param string $method
     * @param callable $callable
     * @param bool $isStatic
     * @param bool|string|null $docComment
     * @return void
     * @throws ShouldNotHappenException
     */
    #[DataProvider("providesTestcases")]
    public function testVariableMethods(
        string $method,
        callable $callable,
        bool $isStatic,
        bool|string|null $docComment
    ) {
        $classReflection = $this->createReflectionProvider()->getClass(MapperClass::class);

        $closureType = $this->closureFactory->fromClosureObject($callable);

        $closureReflection = new ClosureMethodReflection(
            $classReflection,
            $method,
            $closureType,
            $isStatic,
        );

        $closureReflection->setDocDocument($docComment);
        $this->assertEquals($classReflection, $closureReflection->getDeclaringClass());
        $this->assertFalse($closureReflection->isPrivate());
        $this->assertTrue($closureReflection->isPublic());
        $this->assertTrue($closureReflection->isFinal()->no());
        $this->assertTrue($closureReflection->isInternal()->no());

        $this->assertEquals($isStatic, $closureReflection->isStatic());
        if (is_bool($docComment)) {
            $docComment = null;
        }

        $this->assertEquals($docComment, $closureReflection->getDocComment());
        $this->assertEquals($method, $closureReflection->getName());
        $this->assertTrue($closureReflection->isDeprecated()->no());
        $this->assertEquals($closureReflection, $closureReflection->getPrototype());

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
            $closureReflection->getVariants()
        );

        $this->assertNull($closureReflection->getDeprecatedDescription());
        $this->assertNull($closureReflection->getThrowType());
        $this->assertTrue($closureReflection->hasSideEffects()->maybe());
    }
}