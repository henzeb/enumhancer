<?php

namespace Henzeb\Enumhancer\Tests\Unit\PHPStan\Constants\Rule;

use Henzeb\Enumhancer\PHPStan\Constants\Rules\StrictConstantRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class StrictConstantRuleTest extends RuleTestCase
{

    protected function getRule(): Rule
    {
        return new StrictConstantRule();
    }

    public function testNoErrorsIfNotEnum(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Strict/NotEnum.php'],
            [
            ]
        );
    }

    public function testStrictNotAvailable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../../Fixtures/SimpleEnum.php'],
            [
            ]
        );
    }

    public function testStrictCheckIgnoresWhenNotImplementingEnumhancer(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Strict/SimpleStrictEnum.php'],
            [
            ]
        );
    }

    public function testStrictErrorWhenImplementingEnumhancer(): void
    {
        $this->analyse(
            [__DIR__ . '/../../Fixtures/Strict/IncorrectEnum.php'],
            [
                [
                    'Enumhancer: constant `STRICT` should be a boolean.',
                    07
                ]
            ]
        );
    }

    public function testStrictCheckNoErrorsWhenEntirelyCorrect(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../Fixtures/Strict/CorrectStrictTrueEnum.php',
                __DIR__ . '/../../Fixtures/Strict/CorrectStrictFalseEnum.php'
            ],
            [
            ]
        );
    }

}
