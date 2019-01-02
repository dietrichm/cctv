<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\FilesystemInterface;
use League\Uri\Uri;

final class CameraServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        CameraRepository::class,
        SnapshotUnavailableMiddleware::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(CameraRepository::class, function () {
            return new InMemoryCameraRepository(
                $this->getCameras()
            );
        });

        $this->getContainer()->share(SnapshotUnavailableMiddleware::class, function () {
            return new SnapshotUnavailableMiddleware(
                $this->getContainer()->get(FilesystemInterface::class),
                'resources/images/offline.jpg'
            );
        });
    }

    /**
     * @return Camera[]
     */
    private function getCameras(): array
    {
        $cameras = [];

        for ($index = 1; true; ++$index) {
            $prefix = 'CAMERA_' . $index . '_';

            $name = getenv($prefix . 'NAME');
            $snapshotUri = getenv($prefix . 'SNAPSHOT_URI');

            if ($name === false || $snapshotUri === false) {
                break;
            }

            $cameras[] = new Camera(
                $name,
                Uri::createFromString($snapshotUri)
            );
        }

        return $cameras;
    }
}
