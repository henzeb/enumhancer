<?php

namespace Henzeb\Enumhancer\Concerns;

trait Enhancers
{
    use Bitmasks,
        Comparison,
        Defaults,
        Dropdown,
        From,
        Labels,
        Mappers,
        Properties,
        State,
        Subset,
        Value;
}
