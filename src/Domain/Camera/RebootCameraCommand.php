<?php

namespace Detroit\Cctv\Domain\Camera;

final class RebootCameraCommand
{
    /**
     * @var string
     */
    private $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
