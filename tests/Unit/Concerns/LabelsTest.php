<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\Labels;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Mockery;
use PHPUnit\Framework\TestCase;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Labels\LabelNoLabelsEnum;


class LabelsTest extends TestCase
{
    public function testShouldGetNameWhenNoLabelsSpecifiedAtAll()
    {

        $this->assertEquals('NO_LABEL', LabelNoLabelsEnum::NO_LABEL->label());
        $this->assertEquals('nolabel', LabelNoLabelsEnum::nolabel->label());

        $this->assertEquals('NoLabel', LabelNoLabelsEnum::NoLabel->label());
    }

    public function testShouldGetLabelByName()
    {
        $this->assertEquals('My label', EnhancedBackedEnum::ENUM->label());
    }

    public function testShouldGetValueWhenLabelDoesNotExist()
    {
        $this->assertEquals('another enum', EnhancedBackedEnum::ANOTHER_ENUM->label());
    }
}
