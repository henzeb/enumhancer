<?php

namespace Henzeb\Enumhancer\Tests\Unit\Laravel\Rules;

use Henzeb\Enumhancer\Laravel\Providers\EnumhancerServiceProvider;
use Henzeb\Enumhancer\Laravel\Rules\IsEnum;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Defaults\DefaultsEnum;
use Illuminate\Validation\Rule;
use Orchestra\Testbench\TestCase;
use TypeError;

class IsEnumTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EnumhancerServiceProvider::class
        ];
    }

    public function testValidateInstance(): void
    {
        $this->assertInstanceOf(IsEnum::class, Rule::isEnum(SimpleEnum::class));
    }

    public function testInstanceValidatesType(): void
    {
        $this->expectException(TypeError::class);
        Rule::isEnum(self::class);
    }

    public static function providesCasesForPasses(): array
    {
        return [
            'simple-passes-1' => [true, SimpleEnum::class, 'open'],
            'simple-passes-2' => [true, SimpleEnum::class, 'OPEN'],
            'simple-passes-3' => [true, SimpleEnum::class, 'Open'],
            'simple-passes-4' => [true, SimpleEnum::class, 'OpeN'],
            'simple-fails' => [false, SimpleEnum::class, 'close'],
            'simple-fails-not-an-enum' => [false, SimpleEnum::class, 'not-enum'],
            'simple-mapper-passes-1' => [true, SimpleEnum::class, 'accessible', ['accessible' => 'open']],
            'default-fails' => [false, DefaultsEnum::class, 'does-not-exists']
        ];
    }

    /**
     * @param bool $expected
     * @param string $type
     * @param string $value
     * @param array|null $mapper
     * @return void
     *
     * @dataProvider providesCasesForPasses
     */
    public function testPasses(bool $expected, string $type, string $value, ?array $mapper = null): void
    {
        $this->assertEquals($expected, Rule::isEnum($type, $mapper)->passes('test', $value));
    }

    public function testMessages(): void
    {
        $translator = app('translator');
        $rule = Rule::isEnum(SimpleEnum::class);

        $this->assertEquals(['The selected :attribute is invalid.'], $rule->message());

        $translator->addLines(
            [
                'validation.enumhancer.enum' => '`:value` is not a valid :enum value.'
            ],
            $translator->locale()
        );

        $this->assertEquals('`` is not a valid Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum value.', $rule->message());

        $rule->passes('should', 'fail');

        $this->assertEquals('`fail` is not a valid Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum value.',
            $rule->message());
    }
}
