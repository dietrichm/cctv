<?php

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Detroit\Cctv\Application\Camera\GetSnapshotRequestHandler;
use Detroit\Cctv\Application\Camera\ListSnapshotsRequestHandler;
use Detroit\Cctv\Application\Camera\SnapshotUnavailableMiddleware;
use Detroit\Cctv\Infrastructure\FilesystemServiceProvider;
use Detroit\Cctv\Infrastructure\Http\TemplateServiceProvider;
use Jenssegers\Lean\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App();

$app->getContainer()->addServiceProvider(FilesystemServiceProvider::class);
$app->getContainer()->addServiceProvider(TemplateServiceProvider::class);
$app->getContainer()->addServiceProvider(CameraServiceProvider::class);

$app->get('/', ListSnapshotsRequestHandler::class);
$app->get('/snapshot/{cameraName}', GetSnapshotRequestHandler::class)
    ->setName('cameraSnapshot')
    ->add(SnapshotUnavailableMiddleware::class);

$app->run();
