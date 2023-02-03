<?php

namespace Henzeb\Enumhancer\PHPStan\Constants\Rules;

use Henzeb\Enumhancer\Helpers\EnumImplements;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Type\Enum\EnumCaseObjectType;
use ReflectionException;

/**
 * @phpstan-implements Rule<ClassConst>
 */
class DefaultConstantRule implements Rule
{
    public function getNodeType(): string
    {
        return ClassConst::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $constantName = $node->consts[0]->name->name;

        if ($this->isDefault($constantName)) {
            return [];
        }

        $reflectedClass = $scope->getClassReflection();

        if (!$reflectedClass->isEnum()) {
            return [];
        }

        return $this->validate($reflectedClass, $constantName);
    }

    /**
     * @param ClassReflection|null $reflectedClass
     * @param string $constantName
     * @return string[]
     * @throws ReflectionException
     */
    protected function validate(?ClassReflection $reflectedClass, string $constantName): array
    {
        $implementsDefault = EnumImplements::defaults($reflectedClass->getName());

        $value = $reflectedClass->getConstant($constantName)->getValueType();

        $valueFromEnum = $value instanceof EnumCaseObjectType && $value->getClassName() === $reflectedClass->getName();

        $return = [];

        if ($implementsDefault && !$valueFromEnum) {
            $return = [
                sprintf(
                    'Enumhancer: enum is implementing `Defaults`, '
                    . 'but constant `%s` is not referencing to one of its own cases.',
                    $constantName
                )
            ];
        }

        if (!$implementsDefault && $valueFromEnum) {
            $return = [
                sprintf(
                    'Enumhancer: Constant `%s` is not going to be used, '
                    . 'because enum is not implementing `Defaults`',
                    $constantName
                )
            ];
        }

        return $return;
    }

    protected function isDefault(string $constantName): bool
    {
        return strtolower($constantName) !== 'default';
    }
}
