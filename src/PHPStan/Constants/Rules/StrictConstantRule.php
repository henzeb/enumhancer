<?php

namespace Henzeb\Enumhancer\PHPStan\Constants\Rules;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassConstantsNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;

/**
 * @phpstan-implements Rule<ClassConstantsNode>
 */
class StrictConstantRule implements Rule
{
    public function getNodeType(): string
    {
        return ClassConstantsNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $constantName = $node->getConstants()[0]->consts[0]->name->name;

        if (strtolower($constantName) !== 'strict') {
            return [];
        }

        $class = $scope->getClassReflection();

        if ($this->shouldProcessEnum($class)) {
            return [];
        }

        if ($class->getConstant($constantName)->getValueType()->isBoolean()->no()) {
            return [
                sprintf('Enumhancer: constant `%s` should be a boolean.', $constantName)
            ];
        }

        return [];
    }

    /**
     * @param ClassReflection|null $class
     * @return bool
     */
    protected function shouldProcessEnum(?ClassReflection $class): bool
    {
        return !$class->isEnum() || !EnumImplements::enumhancer($class->getName());
    }
}
