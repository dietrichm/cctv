<?php

namespace Detroit\Cctv\Domain\Camera;

use League\Uri\Uri;

final class Camera
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Uri
     */
    private $snapshotUri;

    public function __construct(
        string $name,
        Uri $snapshotUri
    ) {
        $this->name = $name;
        $this->snapshotUri = $snapshotUri;
    }
}
