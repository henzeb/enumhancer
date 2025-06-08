<?php

use Composer\Composer;
use Composer\Config;
use Composer\Package\RootPackageInterface;
use Composer\Script\Event;
use Henzeb\Enumhancer\Composer\IdeHelper;

afterEach(function () {
    if (file_exists('./bootstrap/app.php')) {
        unlink('./bootstrap/app.php');
        rmdir('./bootstrap');
    }
    \Mockery::close();
});
test('should not run when not dev mode', function () {
    $event = \Mockery::mock(Event::class);
    $event->expects('isDevMode')->andReturn(false);

    IdeHelper::generate($event);
});

test('should not run if ide helper package is not installed', function () {
    $event = Mockery::mock(Event::class);
    $event->expects('isDevMode')->andReturn(true);
    $composer = \Mockery::mock(Composer::class);
    $event->expects('getComposer')->andReturn($composer);
    $config = \Mockery::mock(Config::class);
    $config->expects('get')->with('vendor-dir')->andReturn(realpath('./vendor'));
    $composer->expects('getConfig')->andReturn($config);
    $package = \Mockery::mock(RootPackageInterface::class);
    $composer->expects('getPackage')->andReturn($package);

    $package->expects('getExtra')->andReturn([]);
    $package->expects('getRequires')->andReturn([]);
    $package->expects('getDevRequires')->andReturn([]);

    IdeHelper::generate($event);
});


test('should run with default vendor', function () {
    $event = Mockery::mock(Event::class);
    $event->expects('isDevMode')->andReturn(true);
    $composer = Mockery::mock(Composer::class);
    $event->expects('getComposer')->andReturn($composer);
    $config = Mockery::mock(Config::class);
    $config->expects('get')->with('vendor-dir')->andReturn(realpath('./vendor'));
    $composer->expects('getConfig')->andReturn($config);
    $package = Mockery::mock(RootPackageInterface::class);
    $composer->expects('getPackage')->andReturn($package);

    $package->expects('getExtra')->andReturn([]);
    $package->expects('getRequires')->andReturn([]);
    $package->expects('getDevRequires')->andReturn([
        'henzeb/enumhancer-ide-helper' => 'require'
    ]);
    $event->expects('isDevMode')->andReturn(false);

    IdeHelper::generate($event);
});

test('should fail with user specified bootstrap', function () {
    $event = Mockery::mock(Event::class);
    $event->expects('isDevMode')->andReturn(true);
    $composer = Mockery::mock(Composer::class);
    $event->expects('getComposer')->andReturn($composer);
    $config = Mockery::mock(Config::class);
    $config->expects('get')->with('vendor-dir')->andReturn(realpath('./vendor'));
    $composer->expects('getConfig')->andReturn($config);
    $package = Mockery::mock(RootPackageInterface::class);
    $composer->expects('getPackage')->andReturn($package);

    $package->expects('getExtra')->andReturn([
        'enumhancer' => ['ide-helper' => './bootstrap.php']
    ]);

    IdeHelper::generate($event);
})->throws(\RuntimeException::class);

test('should run with user specified bootstrap', function () {
    $event = Mockery::mock(Event::class);
    $event->expects('isDevMode')->andReturn(true);
    $composer = Mockery::mock(Composer::class);
    $event->expects('getComposer')->andReturn($composer);
    $config = Mockery::mock(Config::class);
    $config->expects('get')->with('vendor-dir')->andReturn(realpath('./vendor'));
    $composer->expects('getConfig')->andReturn($config);
    $package = Mockery::mock(RootPackageInterface::class);
    $composer->expects('getPackage')->andReturn($package);

    $package->expects('getDevRequires')->andReturn([]);

    $package->expects('getExtra')->andReturn([
        'enumhancer' => ['ide-helper' => __DIR__ . '/Fixtures/bootstrap.php']
    ]);

    IdeHelper::generate($event);
});

test('should run with different vendor', function () {
    $event = Mockery::mock(Event::class);
    $event->expects('isDevMode')->andReturn(true);
    $composer = Mockery::mock(Composer::class);
    $event->expects('getComposer')->andReturn($composer);
    $config = Mockery::mock(Config::class);
    $config->expects('get')->with('vendor-dir')->andReturn(__DIR__ . '/Fixtures/');
    $composer->expects('getConfig')->andReturn($config);
    $package = Mockery::mock(RootPackageInterface::class);
    $composer->expects('getPackage')->andReturn($package);

    $package->expects('getExtra')->andReturn([]);
    $package->expects('getRequires')->andReturn([]);
    $package->expects('getDevRequires')->andReturn([
        'henzeb/enumhancer-ide-helper' => 'require'
    ]);

    $event->expects('isDevMode')->andReturn(false);

    IdeHelper::generate($event);
});

test('should run with different vendor and runtime autoload', function () {
    $event = Mockery::mock(Event::class);
    $event->expects('isDevMode')->andReturn(true);
    $composer = Mockery::mock(Composer::class);
    $event->expects('getComposer')->andReturn($composer);
    $config = Mockery::mock(Config::class);
    $config->expects('get')->with('vendor-dir')->andReturn(__DIR__ . '/Fixtures/Runtime/');
    $composer->expects('getConfig')->andReturn($config);
    $package = Mockery::mock(RootPackageInterface::class);
    $composer->expects('getPackage')->andReturn($package);

    $package->expects('getExtra')->andReturn([]);
    $package->expects('getRequires')->andReturn([]);
    $package->expects('getDevRequires')->andReturn([
        'henzeb/enumhancer-ide-helper' => 'require'
    ]);
    $event->expects('isDevMode')->andReturn(false);

    IdeHelper::generate($event);
});

test('should run with laravel', function () {
    $event = Mockery::mock(Event::class);
    $event->expects('isDevMode')->times(3)->andReturn(true);
    $composer = Mockery::mock(Composer::class);
    $event->expects('getComposer')->times(3)->andReturn($composer);
    $config = Mockery::mock(Config::class);
    $config->expects('get')->with('vendor-dir')->times(3)->andReturn(realpath('./vendor'));
    $composer->expects('getConfig')->times(3)->andReturn($config);
    $package = Mockery::mock(RootPackageInterface::class);
    $composer->expects('getPackage')->times(2)->andReturn($package);

    $package->expects('getExtra')->twice()->andReturn([]);
    $package->expects('getRequires')->twice()->andReturn([
        'laravel/framework' => 'require'
    ]);
    $package->expects('getDevRequires')->twice()->andReturn([
        'henzeb/enumhancer-ide-helper' => 'require'
    ]);
    $event->expects('isDevMode')->andReturn(false);
    mkdir('./bootstrap');

    copy('./vendor/orchestra/testbench-core/laravel/bootstrap/app.php', './bootstrap/app.php');

    IdeHelper::generate($event);

    expect(app()->hasBeenBootstrapped())->toBeTrue();

    /**
     * when require_once is done twice, it returns a bool, which should fail as $app is thus 'true'
     * and not an app.
     *
     * This last step makes sure that it does not, if for example, run from within laravel or in tandem
     * in a user function where laravel is already loaded.
     */
    IdeHelper::generate($event);

    restore_error_handler();
    restore_exception_handler();
});

