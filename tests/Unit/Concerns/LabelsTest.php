<?php

namespace Henzeb\Enumhancer\Tests\Unit\Concerns;

use Henzeb\Enumhancer\Concerns\Labels;
use Henzeb\Enumhancer\Tests\Fixtures\EnhancedBackedEnum;
use Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Labels\LabelByKeyEnum;
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

    public function testShouldGetLabelByKey(): void
    {
        $this->assertEquals('label 1', LabelByKeyEnum::LabelByKey->label());

        $this->assertEquals('label 2', LabelByKeyEnum::LabelByKey2->label());

        $this->assertEquals('label 2', LabelByKeyEnum::LabelByKey2->label());
    }
}
