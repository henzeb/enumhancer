<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants\Rule;

use Henzeb\Enumhancer\PHPStan\Constants\Rules\DefaultConstantRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class DefaultConstantRuleTest extends RuleTestCase
{

    protected function getRule(): Rule
    {
        return new DefaultConstantRule();
    }

    public function testNoErrorsIfNotEnum(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Defaults/NotEnum.php'],
            [
            ]
        );
    }

    public function testNoErrorsIfConstantNotDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../../Fixtures/SimpleEnum.php'],
            [
            ]
        );
    }

    public function testNoErrorsIfNotImplementingAndNotFromEnum(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Defaults/EnumWithIncorrectDefaultNotImplementing.php'],
            [
            ]
        );
    }

    public function testErrorsIfImplementingAndNotFromEnum(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Defaults/EnumWithIncorrectDefault.php'],
            [
                [
                    'Enumhancer: enum is implementing `Defaults`, '
                    . 'but constant `DEFAULT` is not referencing to one of its own cases.',
                    13,
                ]
            ]
        );
    }

    public function testErrorsIfNotImplementingAndCorrectReference(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Defaults/EnumWithDefaultNotImplementing.php'],
            [
                [
                    'Enumhancer: Constant `Default` is not going to be used, because enum is not implementing `Defaults`',
                    9,
                ]
            ]
        );
    }

    public function testNoErrorsIfImplementingAndCorrectReference(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../../Fixtures/UnitEnums/Defaults/DefaultsConstantEnum.php'],
            [
            ]
        );
    }
}
