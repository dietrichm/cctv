<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
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
}
