<?php

namespace Detroit\Cctv\Domain\Camera;

interface CameraRepository
{
    /**
     * @return Camera[]
     */
    public function findAll(): array;
}
