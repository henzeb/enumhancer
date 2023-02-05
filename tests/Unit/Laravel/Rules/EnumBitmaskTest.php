<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Rules;

use ErrorException;
use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Laravel\Rules\EnumBitmask;
use Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Illuminate\Validation\Rule;
use Orchestra\Testbench\TestCase;
use TypeError;

class EnumBitmaskTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            EnumhancerServiceProvider::class
        ];
    }

    public function testValidateInstance(): void
    {
        $this->assertInstanceOf(EnumBitmask::class, Rule::enumBitmask(BitmasksIntEnum::class));
    }

    public function testInstanceValidatesTypeThatDoesNotImplementBitmasks(): void
    {
        $this->expectException(ErrorException::class);
        Rule::enumBitmask(SimpleEnum::class);
    }

    public function testInstanceValidatesType(): void
    {
        $this->expectException(TypeError::class);
        Rule::enumBitmask(self::class);
    }

    public function providesTestCases(): array
    {
        return [
            'zero-bit-success' => [true, 0],
            'negative-bit-fail' => [false, -1],
            'string-fail' => [false, 'string'],

            'simple-bit-success-1' => [true, 8],
            'simple-bit-success-2' => [true, '8'],
            'simple-bit-fail' => [false, 4],

            'multiple-bits-success' => [true, 24],
            'multiple-bits-fail' => [false, 20],
        ];
    }

    /**
     * @param bool $expected
     * @param mixed $value
     * @return void
     *
     * @dataProvider providesTestCases
     */
    public function testPasses(bool $expected, mixed $value)
    {
        $this->assertEquals($expected, Rule::enumBitmask(BitmasksIntEnum::class)->passes('test', $value));
    }

    public function providesSingleBitTestCases(): array
    {
        return [
            'zero-bit-success' => [true, 0],
            'negative-bit-fail' => [false, -1],
            'string-fail' => [false, 'string'],

            'simple-bit-success-1' => [true, 8],
            'simple-bit-success-2' => [true, '8'],
            'simple-bit-fail' => [false, 4],

            'multiple-bits-fail' => [false, 24],
        ];
    }

    /**
     * @param bool $expected
     * @param mixed $value
     * @return void
     *
     * @dataProvider providesSingleBitTestCases
     */
    public function testPassesSingleBit(bool $expected, mixed $value)
    {
        $this->assertEquals($expected, Rule::enumBitmask(BitmasksIntEnum::class, true)->passes('test', $value));
    }

    public function testMessages(): void
    {
        $rule = Rule::enumBitmask(BitmasksIntEnum::class);

        $this->assertEquals(['The selected :attribute is invalid.'], $rule->message());

        $translator = app('translator');
        $translator->addLines(
            [
                'validation.enumhancer.bitmask' => '`:value` is not a valid :enum value.'
            ],
            $translator->locale()
        );

        $this->assertEquals('`` is not a valid Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum value.',
            $rule->message());

        $rule->passes('should', -1);

        $this->assertEquals('`-1` is not a valid Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\Bitmasks\BitmasksIntEnum value.',
            $rule->message());
    }
}
