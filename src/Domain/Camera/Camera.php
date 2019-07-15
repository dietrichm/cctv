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
     * @var int
     */
    private $requestTimeout;

    /**
     * @var Uri|null
     */
    private $rebootUri;

    public function __construct(
        string $name,
        Uri $snapshotUri,
        int $requestTimeout
    ) {
        $this->name = $name;
        $this->snapshotUri = $snapshotUri;
        $this->requestTimeout = $requestTimeout;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSnapshotUri(): Uri
    {
        return $this->snapshotUri;
    }

    public function hasRebootUri(): bool
    {
        return $this->rebootUri !== null;
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
