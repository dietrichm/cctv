<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Domain\Camera\CameraRepository;

class InMemoryCameraRepository implements CameraRepository
{
    /**
     * @var Camera[]
     */
    private $cameras = [];

    /**
     * @param Camera[] $cameras
     */
    public function __construct(array $cameras)
    {
        foreach ($cameras as $camera) {
            $this->addCamera($camera);
        }
    }

    private function addCamera(Camera $camera): void
    {
        $this->cameras[$camera->getName()] = $camera;
    }

    public function findAll(): array
    {
        return array_values($this->cameras);
    }

    public function findByName(string $name): Camera
    {
        if (!array_key_exists($name, $this->cameras)) {
            throw CameraNotFound::withName($name);
        }

        return $this->cameras[$name];
    }

    public function findRebootable(): array
    {
        return array_values(
            array_filter(
                $this->cameras,
                function (Camera $camera) {
                    return $camera->hasRebootUri();
                }
            )
        );
    }
}
