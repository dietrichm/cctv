<?php

namespace Detroit\Cctv\Domain\Camera;

interface CameraFactory
{
    /**
     * @return Camera[]
     */
    public function createAll(): array;
}
