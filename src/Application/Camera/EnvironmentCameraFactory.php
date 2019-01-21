<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraFactory;
use League\Uri\Uri;

class EnvironmentCameraFactory implements CameraFactory
{
    public function createAll(): array
    {
        $index = 1;
        $cameras = [];

        while ($camera = $this->createCamera($index++)) {
            $cameras[] = $camera;
        }

        return $cameras;
    }

    private function createCamera(int $index): ?Camera
    {
        $prefix = 'CAMERA_' . $index . '_';

        $name = getenv($prefix . 'NAME');
        $snapshotUri = getenv($prefix . 'SNAPSHOT_URI');
        $rebootUri = getenv($prefix . 'REBOOT_URI');

        if ($name === false || $snapshotUri === false) {
            return null;
        }

        $camera = new Camera(
            $name,
            Uri::createFromString($snapshotUri)
        );

        if (!empty($rebootUri)) {
            $camera->setRebootUri(Uri::createFromString($rebootUri));
        }

        return $camera;
    }
}
