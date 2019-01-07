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

    /**
     * @var Uri|null
     */
    private $rebootUri;

    public function __construct(
        string $name,
        Uri $snapshotUri
    ) {
        $this->name = $name;
        $this->snapshotUri = $snapshotUri;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSnapshotUri(): Uri
    {
        return $this->snapshotUri;
    }

    public function getRebootUri(): ?Uri
    {
        return $this->rebootUri;
    }

    public function setRebootUri(Uri $rebootUri): void
    {
        $this->rebootUri = $rebootUri;
    }
}
