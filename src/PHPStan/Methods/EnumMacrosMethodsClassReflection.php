<?php

namespace Henzeb\Enumhancer\PHPStan\Methods;

use Closure;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Helpers\EnumMacros;
use Henzeb\Enumhancer\PHPStan\Reflections\ClosureMethodReflection;
use LogicException;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ClosureTypeFactory;
use ReflectionFunction;

class EnumMacrosMethodsClassReflection implements MethodsClassReflectionExtension
{
    public function __construct(
        private readonly ClosureTypeFactory $closureTypeFactory,
    ) {
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (!$classReflection->isEnum()) {
            return false;
        }

        $className = $classReflection->getName();

        if (EnumImplements::macros($className)) {
            return $this->getMacro($className, $methodName) !== null;
        }

        return false;
    }

    public function getMethod(
        ClassReflection $classReflection,
        string $methodName
    ): MethodReflection {
        $className = $classReflection->getName();
        $macro = $this->getMacro($className, $methodName);
        /**
         * PHPStan does not support isStatic on closureType
         * @phpstan-ignore-next-line
         */
        $nativeReflection = new ReflectionFunction($macro);

        try {
            return (new ClosureMethodReflection(
                $classReflection,
                $methodName,
                $this->closureTypeFactory->fromClosureObject(
                    $macro
                ),
                $nativeReflection->isStatic(),
            ))->setDocDocument(
                $nativeReflection->getDocComment()
            );
        } catch (LogicException) {
            /**
             * Transforming Illogic Exception into an explanatory Logically clear exception.
             */
            throw new LogicException(
                sprintf(
                    'PHPStan Could not properly parse closure `%s` for `%s`, '
                    . 'Check if a default value\'s code is not trying to execute this macro.',
                    $methodName,
                    $className
                )
            );
        }
    }

    public function getMacro(string $class, string $macro): ?Closure
    {
        return Closure::bind(
            function (string $class, string $macro) {
                return EnumMacros::getMacro($class, $macro);
            },
            null,
            EnumMacros::class
        )($class, $macro);
    }
}
