<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Labels;

use Henzeb\Enumhancer\Concerns\Labels;

enum LabelNoLabelsEnum
{
    use Labels;

    case NoLabel;
    case NO_LABEL;
    case nolabel;
}
