<?php

namespace Unit\Helpers;

use Henzeb\Enumhancer\Helpers\EnumMakers;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use Henzeb\Enumhancer\Helpers\MultiEnumMethods;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedUnitEnum;
use Henzeb\Enumhancer\Tests\Fixtures\IntBackedMakersEnum;

class MultiEnumMethodsTest extends TestCase
{

    public function testShouldThrowErrorWithWrongEnumType(): void
    {
        $this->expectError();
        (new MultiEnumMethods(IntBackedMakersEnum::class, EnhancedUnitEnum::ENUM));
    }


    public function testEqualsShouldReturnNullWhenNoEnumsPassed()
    {
        $this->assertFalse(
            (new MultiEnumMethods(IntBackedMakersEnum::class))
                ->equals(IntBackedMakersEnum::TEST)
        );
    }

    public function testEqualsShouldReturnTrue()
    {
        $this->assertTrue(
            (new MultiEnumMethods(IntBackedMakersEnum::class, IntBackedMakersEnum::TEST))
                ->equals(IntBackedMakersEnum::TEST)
        );
    }

    public function testEqualsMultiShouldReturnTrue()
    {
        $this->assertTrue(
            (new MultiEnumMethods(IntBackedMakersEnum::class, ...IntBackedMakersEnum::cases()))
                ->equals(IntBackedMakersEnum::TEST)
        );
    }
}
