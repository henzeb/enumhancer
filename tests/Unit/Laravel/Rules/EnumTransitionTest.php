<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Rules;

use ErrorException;
use Henzeb\Enumhancer\Contracts\TransitionHook;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Laravel\Rules\EnumTransition;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorComplexEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\State\StateElevatorEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchestra\Testbench\TestCase;
use UnitEnum;

class EnumTransitionTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EnumhancerServiceProvider::class
        ];
    }

    protected function providesFailingValidationTestCases(): array
    {
        return [
            'basic-open-move' => [false, StateElevatorEnum::Open, 'Move'],
            'basic-open-stop' => [false, StateElevatorEnum::Open, 'Stop'],

            'basic-close-stop' => [false, StateElevatorEnum::Close, 'stop'],
            'basic-close-open' => [false, StateElevatorEnum::Close, 'open'],

            'basic-move-close' => [false, StateElevatorEnum::Move, 'close'],
            'basic-move-open' => [false, StateElevatorEnum::Move, 'open'],

            'basic-stop-open' => [false, StateElevatorEnum::Stop, 'open'],
            'basic-stop-close' => [false, StateElevatorEnum::Stop, 'close'],
            'basic-stop-move' => [false, StateElevatorEnum::Stop, 'move'],

            'complex-open-move' => [false, StateElevatorComplexEnum::Open, 'move'],
            'complex-open-stop' => [false, StateElevatorComplexEnum::Open, 'stop'],

            'complex-close-stop' => [false, StateElevatorComplexEnum::Close, 'stop'],

            'complex-move-open' => [false, StateElevatorComplexEnum::Move, 'open'],
            'complex-move-close' => [false, StateElevatorComplexEnum::Move, 'close'],

            'complex-stop-move' => [false, StateElevatorComplexEnum::Stop, 'move'],
            'complex-stop-close' => [false, StateElevatorComplexEnum::Stop, 'close'],

        ];
    }

    public function providesAllowedValidationCases(): array
    {
        return [
            'basic-open-close' => [true, StateElevatorEnum::Open, 'close'],
            'basic-close-move' => [true, StateElevatorEnum::Close, 'move'],
            'basic-move-stop' => [true, StateElevatorEnum::Move, 'stop'],

            'complex-open-close' => [true, StateElevatorComplexEnum::Open, 'Close'],
            'complex-close-open' => [true, StateElevatorComplexEnum::Close, 'Open'],
            'complex-close-move' => [true, StateElevatorComplexEnum::Close, 'Move'],
            'complex-move-stop' => [true, StateElevatorComplexEnum::Move, 'stop'],
            'complex-stop-open' => [true, StateElevatorComplexEnum::Stop, 'open'],
        ];
    }

    /**
     * @param bool $expected
     * @param UnitEnum $from
     * @param string $to
     * @return void
     *
     * @dataProvider providesFailingValidationTestCases
     * @dataProvider providesAllowedValidationCases
     */
    public function testValidateTransition(bool $expected, UnitEnum $from, string $to)
    {
        $this->assertEquals($expected,
            !Validator::make(
                [
                    'state' => $to
                ],
                [
                    'state' => [Rule::enumTransition($from)]
                ]
            )->fails()
        );
    }

    public function testRuleMacroReturnsCorrectRule(): void
    {
        $this->assertInstanceOf(
            EnumTransition::class,
            Rule::enumTransition(StateElevatorComplexEnum::Open)
        );
    }

    public function testRuleThrowsErrorWhenNotImplementingState(): void
    {
        $this->expectException(ErrorException::class);
        Rule::enumTransition(SimpleEnum::Open);
    }


    public function testValidateTransitionFailsWithHook()
    {
        $hook = new class extends TransitionHook {
            protected function allowsOpenClose(): bool
            {
                return false;
            }
        };

        $this->assertEquals(true,
            Validator::make(
                [
                    'state' => StateElevatorEnum::Close->name
                ],
                [
                    'state' => [Rule::enumTransition(StateElevatorEnum::Open, $hook)]
                ]
            )->fails()
        );
    }

    public function testValidateTransitionWithHook()
    {
        $hook = new class extends TransitionHook {
            protected function allowsOpenClose(): bool
            {
                return true;
            }
        };

        $this->assertEquals(false,
            Validator::make(
                [
                    'state' => StateElevatorEnum::Close->name
                ],
                [
                    'state' => [Rule::enumTransition(StateElevatorEnum::Open, $hook)]
                ]
            )->fails()
        );
        \Mockery::close();
    }

    public function testBasicValidationMessage()
    {
        $this->assertEquals(
            'The transition for state is invalid.',
            Validator::make(
                [
                    'state' => 'Open'
                ],
                [
                    'state' => [Rule::enumTransition(StateElevatorEnum::Move)]
                ]
            )->errors()->get('state')[0]);
    }

    public function providesTranslationTestcases(): array
    {
        return [
            ['Transition from `:from` to `:to` is not allowed', 'Transition from `Move` to `Open` is not allowed'],
            ['Transition to `:to` from `:from` is not allowed', 'Transition to `Open` from `Move` is not allowed']
        ];
    }

    /**
     * @param string $message
     * @param string $expected
     * @return void
     * @dataProvider providesTranslationTestcases
     */
    public function testTranslatedValidationMessage(string $message, string $expected)
    {
        $translator = app('translator');
        $translator->addLines(
            [
                'validation.enumhancer.transition' => $message
            ],
            $translator->locale());
        $this->assertEquals(
            $expected,
            Validator::make(
                [
                    'state' => 'Open'
                ],
                [
                    'state' => [Rule::enumTransition(StateElevatorEnum::Move)]
                ]
            )->errors()->get('state')[0]);
    }
}
