<?php

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Detroit\Cctv\Application\Camera\GetSnapshotRequestHandler;
use Detroit\Cctv\Application\Camera\ListSnapshotsRequestHandler;
use Detroit\Cctv\Application\Camera\SnapshotUnavailableMiddleware;
use Detroit\Cctv\Infrastructure\FilesystemServiceProvider;
use Detroit\Cctv\Infrastructure\TwigServiceProvider;
use Dotenv\Dotenv;
use Jenssegers\Lean\App;
use Slim\Settings;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__ . '/..');
$dotenv->overload();

$app = new App();

$app->getContainer()->get(Settings::class)->set(
    'routerCacheFile',
    getenv('TMP_DIR') . '/router-cache.php'
);

$app->getContainer()->addServiceProvider(CameraServiceProvider::class);
$app->getContainer()->addServiceProvider(FilesystemServiceProvider::class);
$app->getContainer()->addServiceProvider(TwigServiceProvider::class);

$app->get('/', ListSnapshotsRequestHandler::class);
$app->get('/snapshot/{cameraName}', GetSnapshotRequestHandler::class)
    ->setName('cameraSnapshot')
    ->add(SnapshotUnavailableMiddleware::class);

$app->run();
