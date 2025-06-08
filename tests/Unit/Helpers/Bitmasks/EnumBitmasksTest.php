<?php

use Henzeb\Enumhancer\Helpers\Bitmasks\EnumBitmasks;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;

test('isModifier should return false when ignoreIntValues returns true', function () {
    // SimpleEnum doesn't have BIT_VALUES constant, so ignoreIntValues will return true
    // This should hit line 81: return false;
    expect(EnumBitmasks::isModifier(SimpleEnum::class))->toBeFalse();
});