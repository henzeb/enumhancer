<?php

use Henzeb\Enumhancer\Helpers\EnumImplements;
use Henzeb\Enumhancer\Tests\Fixtures\SimpleEnum;

test('implements should fail', function () {
    set_error_handler(fn($code, $message) => throw new \Exception($message));

    try {
        EnumImplements::doesNotExist(SimpleEnum::class);

    } catch (\Throwable $e) {
        
        expect($e->getMessage())->toBe(
            'Call to undefined method Henzeb\Enumhancer\Helpers\EnumImplements::doesNotExist()'
        );
    } finally {
        restore_error_handler();
    }
});
