<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraFactory;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\FilesystemInterface;

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
        $this->getContainer()->share(CameraFactory::class, function () {
            return new EnvironmentCameraFactory();
        });

        $this->getContainer()->share(CameraRepository::class, function () {
            /** @var CameraFactory $cameraFactory */
            $cameraFactory = $this->getContainer()->get(CameraFactory::class);

            return new InMemoryCameraRepository(
                $cameraFactory->createAll()
            );
        });

        $this->getContainer()->share(SnapshotUnavailableMiddleware::class, function () {
            return new SnapshotUnavailableMiddleware(
                $this->getContainer()->get(FilesystemInterface::class),
                'resources/images/offline.jpg'
            );
        });
    }
}
