<?php

namespace Henzeb\Enumhancer\Composer;

use Composer\Package\RootPackageInterface;
use Composer\Script\Event;
use Henzeb\Enumhancer\Idehelper\EnumIdeHelper;
use Illuminate\Foundation\Console\Kernel;
use RuntimeException;
use function file_exists;
use function is_bool;

class IdeHelper
{
    public static function generate(Event $event): void
    {
        if (!$event->isDevMode()) {
            return;
        }

        $composer = $event->getComposer();
        $config = $composer->getConfig();
        $package = $composer->getPackage();
        $vendorDir = $config->get('vendor-dir');

        self::requireAutoloader($vendorDir);

        $alreadyBootstrapped = self::requireUserDefinedBootstrap($package);

        if (!$alreadyBootstrapped) {
            self::requireLaravel($package);
        }

        if (self::hasIdeHelperInstalled($package)) {
            EnumIdeHelper::postAutoloadDump($event);
        }
    }

    private static function hasIdeHelperInstalled(RootPackageInterface $package): bool
    {
        $filtered = array_filter(
            $package->getDevRequires(),
            fn($name) => $name === 'henzeb/enumhancer-ide-helper',
            ARRAY_FILTER_USE_KEY
        );

        return count($filtered) > 0;
    }

    private static function requireAutoloader(string $vendorDir): void
    {
        $file = $vendorDir . '/autoload.php';

        if (file_exists($vendorDir . '/autoload_runtime.php')) {
            $file = $vendorDir . '/autoload_runtime.php';
        }

        require_once $file;
    }

    private static function requireUserDefinedBootstrap(RootPackageInterface $package): bool
    {
        $file = $package->getExtra()['enumhancer']['ide-helper'] ?? null;

        if ($file) {
            if (!file_exists($file)) {
                throw new RuntimeException(
                    sprintf(
                        'require_once(%s): Failed to open stream: No such file or directory',
                        $file
                    )
                );
            }
            require_once $file;
            return true;
        }
        return false;
    }

    private static function requireLaravel(RootPackageInterface $package): void
    {
        $filtered = array_filter(
            $package->getRequires(),
            fn(string $name) => $name === 'laravel/framework',
            ARRAY_FILTER_USE_KEY
        );
        $realPath = realpath('./bootstrap/app.php');
        $hasLaravel = count($filtered) > 0
            && is_string($realPath)
            && file_exists($realPath);

        if ($hasLaravel) {
            $app = require_once realpath('./bootstrap/app.php');

            /**
             * already bootstrapped
             */
            if (is_bool($app)) {
                return;
            }

            /**
             * @var Kernel $kernel
             */
            $kernel = $app->make(Kernel::class);

            $kernel->bootstrap();
        }
    }
}
