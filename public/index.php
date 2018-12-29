<?php

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Detroit\Cctv\Application\Camera\ListSnapshotsRequestHandler;
use Jenssegers\Lean\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App();

$app->getContainer()->addServiceProvider(CameraServiceProvider::class);

$app->get('/', ListSnapshotsRequestHandler::class);

$app->run();
