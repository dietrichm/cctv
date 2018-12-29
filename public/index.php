<?php

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Detroit\Cctv\Application\Camera\SnapshotsRequestHandler;
use Jenssegers\Lean\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App();

$app->getContainer()->addServiceProvider(CameraServiceProvider::class);

$app->get('/', SnapshotsRequestHandler::class);

$app->run();
