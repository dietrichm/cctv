<?php

namespace Detroit\Cctv\Domain\Camera;

interface CameraRepository
{
    /**
     * @return Camera[]
     */
    public function findAll(): array;

    public function findByName(string $name): Camera;

    /**
     * @return Camera[]
     */
    public function findRebootable(): array;
}
