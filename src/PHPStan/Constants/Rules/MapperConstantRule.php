<?php

namespace Henzeb\Enumhancer\PHPStan\Constants\Rules;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MissingConstantFromReflectionException;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use function strtolower;

/**
 * @phpstan-implements Rule<ClassConst>
 */
class MapperConstantRule implements Rule
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getNodeType(): string
    {
        return ClassConst::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $constantName = $node->consts[0]->name->name;

        if (!$this->isMapperConstant($constantName)) {
            return [];
        }

        $classReflection = $scope->getClassReflection();

        if (!$classReflection->isEnum()) {
            return [];
        }

        return $this->validate($classReflection, $constantName);
    }

    private function isMapperConstant(string $name): bool
    {
        return str_starts_with(strtolower($name), 'map');
    }

    /**
     * @param ClassReflection|null $class
     * @param string $constantName
     * @return array<int,string>
     * @throws MissingConstantFromReflectionException
     */
    protected function validate(?ClassReflection $class, string $constantName): array
    {
        $implementsMappers = EnumImplements::mappers($class->getName());
        $return = [];

        $isValid = $this->isValidValue($class, $constantName);

        if (!$implementsMappers && $isValid) {
            $return = [
                sprintf(
                    'Enumhancer: `%s` is not going to be used because enum is not implementing `Mappers`',
                    $constantName,
                )
            ];
        }

        if ($implementsMappers && !$isValid) {
            $return = [
                sprintf(
                    'Enumhancer: The specified `%s` map is invalid',
                    $constantName,
                )
            ];
        }

        return $return;
    }

    /**
     * @throws MissingConstantFromReflectionException
     */
    protected function isValidValue(
        ?ClassReflection $class,
        string $constantName
    ): bool {
        $valueType = $class->getConstant($constantName)->getValueType();

        $isValid = $valueType instanceof ConstantArrayType;

        if ($valueType instanceof ConstantStringType) {
            $class = $valueType->getValue();
            $isValid = $valueType->isClassStringType()->yes()
                && $this->reflectionProvider->getClass($class)->isSubclassOf(Mapper::class);
        }

        return $isValid;
    }
}
