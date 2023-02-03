<?php

namespace Henzeb\Enumhancer\Tests\Unit\Composer;

use Composer\Composer;
use Composer\Config;
use Composer\Package\RootPackageInterface;
use Composer\Script\Event;
use Henzeb\Enumhancer\Composer\IdeHelper;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use RuntimeException;
use function copy;
use function file_exists;
use function mkdir;
use function realpath;
use function rmdir;
use function unlink;

class IdeHelperTest extends MockeryTestCase
{
    public function testShouldNotRunWhenNotDevMode()
    {
        $event = Mockery::mock(Event::class);
        $event->expects('isDevMode')->andReturn(false);

        IdeHelper::generate($event);
    }

    public function testShouldNotRunIfIdeHelperPackageIsNotInstalled()
    {
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
        $package->expects('getDevRequires')->andReturn([]);

        IdeHelper::generate($event);
    }

    /*public function testShouldNotCrashWithErrorsIn()
    {
        $event = Mockery::mock(Event::class);
        $event->expects('isDevMode')->andReturn(true);
        $composer = Mockery::mock(Composer::class);
        $event->expects('getComposer')->andReturn($composer);
        $config = Mockery::mock(Config::class);
        $config->expects('get')->with('vendor-dir')->andReturn(\realpath('./vendor'));
        $composer->expects('getConfig')->andReturn($config);
        $package = Mockery::mock(RootPackageInterface::class);
        $composer->expects('getPackage')->andReturn($package);

        $package->expects('getExtra')->andReturn([]);
        $package->expects('getRequires')->andReturn([]);
        $package->expects('getDevRequires')->andReturn([]);

        IdeHelper::postAutoloadDump($event);
    }*/

    public function testShouldRunWithDefaultVendor()
    {
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
    }

    public function testShouldFailWithUserSpecifiedBootstrap()
    {
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

        $this->expectException(RuntimeException::class);

        IdeHelper::generate($event);
    }

    public function testShouldRunWithUserSpecifiedBootstrap()
    {
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
    }

    public function testShouldRunWithDifferentVendor()
    {
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
    }

    public function testShouldRunWithDifferentVendorAndRuntimeAutoload()
    {
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
    }

    public function testShouldRunWithLaravel()
    {
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

        $this->assertTrue(app()->hasBeenBootstrapped());

        /**
         * when require_once is done twice, it returns a bool, which should fail as $app is thus 'true'
         * and not an app.
         *
         * This last step makes sure that it does not, if for example, run from within laravel or in tandem
         * in a user function where laravel is already loaded.
         */
        IdeHelper::generate($event);
    }

    public function tearDown(): void
    {
        if (file_exists('./bootstrap/app.php')) {
            unlink('./bootstrap/app.php');
            rmdir('./bootstrap');
        }
    }
}
