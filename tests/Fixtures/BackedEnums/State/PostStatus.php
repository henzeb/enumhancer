<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\BackedEnums\State;

use Henzeb\Enumhancer\Concerns\State;

enum PostStatus: string
{
    use State;

    case DRAFT = 'DRAFT';
    case READY = 'READY';
    case PUBLISHED = 'PUBLISHED';
    case ARCHIVED = 'ARCHIVED';
}
