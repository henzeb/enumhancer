<?php

use Henzeb\Enumhancer\Tests\TestCase;
use Illuminate\Support\Facades\Config;


beforeEach(function () {
    Config::set('app.key', 'base64:+vvg9yApP0djYSZlVTA0y4QnzdC7icL1U5qExdW4gts=');
});

test('api route no parameters', function () {
    $this->get('/noparams')->assertOk();
});

test('api route binds basic enum', function () {
    $this->get('/simpleapi/open')->assertOk();
});

test('should bind basic enum', function () {
    $this->get('/simple/open')->assertOk();
    $this->get('/simple/Open')->assertOk();
    $this->get('/simple/0')->assertOk();
});

test('should bind basic enum optionally', function () {
    $this->get('/optional/')->assertOk()->assertSee('');
    $this->get('/optional/open')->assertOk()->assertSee('Open');
});

test('should bind basic enum with default', function () {
    $this->get('/default/')->assertOk();
});

test('should bind backed enum', function () {
    $this->get('/backed/third_enum')
        ->assertOk()->assertSeeText('ENUM_3');
    $this->get('/backed/ConstantEnum')->assertOk()
        ->assertSeeText('ENUM_3');
    $this->get('/backed/0')->assertOk()
        ->assertSeeText('ENUM');

    $this->get('/backed/Failed')->assertNotFound();
});

test('should bind int backed enum', function () {
    $this->get('/intbacked/Open')->assertOk();
    $this->get('/intbacked/ConstantEnum')->assertOk();
    $this->get('/intbacked/0')->assertOk();

    $this->get('/intbacked/99')->assertOk();

    $this->get('/intbacked/Failed')->assertNotFound();
});
