<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraRepository;

class InMemoryCameraRepository implements CameraRepository
{
    /**
     * @var Camera[]
     */
    private $cameras;

    /**
     * @param Camera[] $cameras
     */
    public function __construct(array $cameras)
    {
        $this->cameras = $cameras;
    }

    public function findAll(): array
    {
        return $this->cameras;
    }
}
