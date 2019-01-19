<?php

use Detroit\Cctv\Application\App;
use Detroit\Cctv\Application\Camera\GetSnapshotRequestHandler;
use Detroit\Cctv\Application\Camera\ListSnapshotsRequestHandler;
use Detroit\Cctv\Application\Camera\SnapshotUnavailableMiddleware;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__ . '/..');
$dotenv->overload();

$app = new App();

$app->get('/', ListSnapshotsRequestHandler::class);
$app->get('/snapshot/{cameraName}', GetSnapshotRequestHandler::class)
    ->setName('cameraSnapshot')
    ->add(SnapshotUnavailableMiddleware::class);

$app->run();
