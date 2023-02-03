<?php

namespace Henzeb\Enumhancer\Tests\Fixtures;

enum SimpleEnum
{
    case Open;
    case Closed;

    const NoDefault = 'test';
}
