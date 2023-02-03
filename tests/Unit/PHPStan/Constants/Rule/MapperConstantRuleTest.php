<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants\Rule;

use Henzeb\Enumhancer\PHPStan\Constants\Rules\MapperConstantRule;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;


class MapperConstantRuleTest extends RuleTestCase
{

    protected function getRule(): Rule
    {
        return new MapperConstantRule(
            $this->getContainer()->getByType(ReflectionProvider::class)
        );
    }

    public function testNoErrorsIfNotEnum(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Mappers/NotEnum.php'],
            [
            ]
        );
    }

    public function testEnumWithConstantThatIsNotMapperName(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Mappers/EnumWithNonMapperConstant.php'],
            [
            ]
        );
    }

    public function testEnumImplementingMappersWithInvalidMap(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Mappers/MappersEnum.php'],
            [
                [
                    'Enumhancer: The specified `MapInvalid` map is invalid',
                    14,
                ]
            ]
        );
    }

    public function testEnumNotImplementingMappersWithValidMap(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Mappers/SimpleEnumWithMapperConstant.php'],
            [
                [
                    'Enumhancer: `MAP_SELF` is not going to be used because enum is not implementing `Mappers`',
                    07,
                ]
            ]
        );
    }

    public function testMappersConstantContainsMapperClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Mappers/EnumWithMapperClass.php'],
            [
            ]
        );
    }
}
