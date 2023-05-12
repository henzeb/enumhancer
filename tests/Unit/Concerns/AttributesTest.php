<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\AnotherAttribute;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\AttributesEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\ClassAttributesEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Attributes\Description;
use PHPUnit\Framework\TestCase;

class AttributesTest extends TestCase
{
    public function testShouldGetDescription(): void
    {
        $description = AttributesEnum::WithAttribute->getAttribute(Description::class);

        $this->assertInstanceOf(Description::class, $description);

        $this->assertEquals('has description', $description->value);
    }

    public function testShouldGetAnotherAttribute(): void
    {
        $description = AttributesEnum::WithMixedAttributes->getAttribute(AnotherAttribute::class);

        $this->assertInstanceOf(AnotherAttribute::class, $description);
    }


    public function testShouldReturnNullWhenNoDescription(): void
    {
        $description = AttributesEnum::WithoutAttribute->getAttribute(Description::class);

        $this->assertNull($description);
    }

    public function testShouldGetDescriptions(): void
    {
        $descriptions = AttributesEnum::WithMultipleAttributes->getAttributes(Description::class);

        $this->assertCount(2, $descriptions);

        $this->assertInstanceOf(Description::class, $descriptions[0]);
        $this->assertInstanceOf(Description::class, $descriptions[1]);

        $this->assertEquals('has description', $descriptions[0]->value);
        $this->assertEquals('and another one', $descriptions[1]->value);
    }


    public function testShouldGetAllAttributes(): void
    {
        $descriptions = AttributesEnum::WithMultipleAttributes->getAttributes();

        $this->assertCount(2, $descriptions);

        $this->assertInstanceOf(Description::class, $descriptions[0]);
        $this->assertInstanceOf(Description::class, $descriptions[1]);

        $this->assertEquals('has description', $descriptions[0]->value);
        $this->assertEquals('and another one', $descriptions[1]->value);
    }

    public function testShouldGetMixedAttributes(): void
    {
        $descriptions = AttributesEnum::WithMixedAttributes->getAttributes();

        $this->assertCount(2, $descriptions);

        $this->assertInstanceOf(Description::class, $descriptions[0]);
        $this->assertInstanceOf(AnotherAttribute::class, $descriptions[1]);

        $this->assertEquals('has description', $descriptions[0]->value);
    }

    public function testGetAttributesShouldGetEmptyArray(): void
    {
        $descriptions = AttributesEnum::WithoutAttribute->getAttributes(Description::class);

        $this->assertCount(0, $descriptions);
    }

    public function testGetAttributesShouldGetEmptyArrayWithNonExistentAttribute(): void
    {
        $descriptions = AttributesEnum::WithAttribute->getAttributes(AnotherAttribute::class);

        $this->assertCount(0, $descriptions);
    }

    public function testGetEnumAttributeReturnsNull()
    {
        $this->assertNull(AttributesEnum::getEnumAttribute(Description::class));
    }

    public function testGetEnumAttributeReturnsEmpty()
    {
        $this->assertEmpty(AttributesEnum::getEnumAttributes(Description::class));
    }

    public function testGetEnumAttribute()
    {
        $description = ClassAttributesEnum::getEnumAttribute(Description::class);

        $this->assertInstanceOf(Description::class, $description);

        $this->assertEquals('test', $description->value);
    }

    public function testGetEnumAttributesByName()
    {
        $description = ClassAttributesEnum::getEnumAttributes(Description::class);

        $this->assertInstanceOf(Description::class, $description[0]);

        $this->assertEquals('test', $description[0]->value);
    }

    public function testGetEnumAttributesWithoutName()
    {
        $description = ClassAttributesEnum::getEnumAttributes();

        $this->assertCount(2, $description);

        $this->assertInstanceOf(Description::class, $description[0]);
        $this->assertInstanceOf(AnotherAttribute::class, $description[1]);

        $this->assertEquals('test', $description[0]->value);
    }
}
