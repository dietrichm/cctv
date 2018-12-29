<?php

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Jenssegers\Lean\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App();

$app->getContainer()->addServiceProvider(CameraServiceProvider::class);

$app->run();
