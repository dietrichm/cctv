<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraFactory;
use League\Uri\Uri;

class EnvironmentCameraFactory implements CameraFactory
{
    public function createAll(): array
    {
        $cameras = [];

        for ($index = 1; true; ++$index) {
            $prefix = 'CAMERA_' . $index . '_';

            $name = getenv($prefix . 'NAME');
            $snapshotUri = getenv($prefix . 'SNAPSHOT_URI');
            $rebootUri = getenv($prefix . 'REBOOT_URI');

            if ($name === false || $snapshotUri === false) {
                break;
            }

            $camera = new Camera(
                $name,
                Uri::createFromString($snapshotUri)
            );

            if (!empty($rebootUri)) {
                $camera->setRebootUri(Uri::createFromString($rebootUri));
            }

            $cameras[] = $camera;
        }

        return $cameras;
    }
}
