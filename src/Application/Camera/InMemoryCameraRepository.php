<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;

class InMemoryCameraRepository implements CameraRepository
{
    public function findAll(): array
    {
        return [];
    }
}
