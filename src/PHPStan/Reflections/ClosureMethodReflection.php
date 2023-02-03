<?php

namespace Henzeb\Enumhancer\PHPStan\Reflections;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ClosureType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Type;
use function is_bool;

final class ClosureMethodReflection implements MethodReflection
{
    private string|bool|null $docComment = null;

    public function __construct(
        private ClassReflection $classReflection,
        private string $methodName,
        private ClosureType $closureType,
        private bool $isStatic = false
    ) {
    }

    public function setDocDocument(string|bool|null $docComment): self
    {
        $this->docComment = $docComment;
        return $this;
    }


    public function getDeclaringClass(): ClassReflection
    {
        return $this->classReflection;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function isFinal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    public function getDocComment(): ?string
    {
        if (!$this->docComment || is_bool($this->docComment)) {
            return null;
        }

        return $this->docComment;
    }

    public function getName(): string
    {
        return $this->methodName;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    public function getVariants(): array
    {
        return [
            new FunctionVariant(
                TemplateTypeMap::createEmpty(),
                null,
                $this->closureType->getParameters(),
                $this->closureType->isVariadic(),
                $this->closureType->getReturnType()
            ),
        ];
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function getThrowType(): ?Type
    {
        return null;
    }

    public function hasSideEffects(): TrinaryLogic
    {
        return TrinaryLogic::createMaybe();
    }
}
