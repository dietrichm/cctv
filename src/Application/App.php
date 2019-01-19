<?php

namespace Detroit\Cctv\Application;

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Detroit\Cctv\Infrastructure\CommandBusServiceProvider;
use Detroit\Cctv\Infrastructure\FilesystemServiceProvider;
use Detroit\Cctv\Infrastructure\LoggingServiceProvider;
use Detroit\Cctv\Infrastructure\TwigServiceProvider;
use Jenssegers\Lean\App as LeanApp;
use Monolog\ErrorHandler;
use Psr\Log\LoggerInterface;
use Slim\Settings;

class App extends LeanApp
{
    public function __construct()
    {
        parent::__construct();

        $this->registerLogger();

        $this->getContainer()
            ->addServiceProvider(CameraServiceProvider::class)
            ->addServiceProvider(CommandBusServiceProvider::class)
            ->addServiceProvider(FilesystemServiceProvider::class)
            ->addServiceProvider(TwigServiceProvider::class);

        $this->setSettings();
    }

    private function registerLogger(): void
    {
        $this->getContainer()->addServiceProvider(
            LoggingServiceProvider::class
        );

        ErrorHandler::register(
            $this->getContainer()->get(LoggerInterface::class)
        );
    }

    private function setSettings(): void
    {
        $this->getContainer()->get(Settings::class)->set(
            'routerCacheFile',
            getenv('TMP_DIR') . '/router-cache.php'
        );
    }
}
