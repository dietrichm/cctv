<?php

namespace Detroit\Cctv\Tests;

use Detroit\Cctv\Domain\Camera\Camera;
use League\Uri\Uri;

final class CameraBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $requestTimeout;

    /**
     * @var string
     */
    private $snapshotUri;

    /**
     * @var string|null
     */
    private $rebootUri;

    private function __construct()
    {
    }

    public static function create(): self
    {
        $builder = new self();

        $builder->name = 'foo';
        $builder->snapshotUri = 'https://example.org/snapshot';
        $builder->requestTimeout = 2;
        $builder->rebootUri = 'https://example.org/reboot';

        return $builder;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withSnapshotUri(string $snapshotUri): self
    {
        $this->snapshotUri = $snapshotUri;

        return $this;
    }

    public function withRequestTimeout(int $requestTimeout): self
    {
        $clone = clone $this;
        $clone->requestTimeout = $requestTimeout;

        return $clone;
    }

    public function withRebootUri(string $rebootUri): self
    {
        $this->rebootUri = $rebootUri;

        return $this;
    }

    public function withoutRebootUri(): self
    {
        $this->rebootUri = null;

        return $this;
    }

    public function build(): Camera
    {
        $camera = new Camera(
            $this->name,
            Uri::createFromString($this->snapshotUri),
            $this->requestTimeout
        );

        if ($this->rebootUri !== null) {
            $camera->setRebootUri(
                Uri::createFromString($this->rebootUri)
            );
        }

        return $camera;
    }
}
