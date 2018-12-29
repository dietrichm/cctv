<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use GuzzleHttp\Client;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Uri\Uri;

final class CameraServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        CameraRepository::class,
        GetSnapshotRequestHandler::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(CameraRepository::class, function () {
            return new InMemoryCameraRepository([
                new Camera(
                    'gang',
                    Uri::createFromString('http://192.168.1.15/snapshot.cgi?user=anon&pwd=anon')
                ),
                new Camera(
                    'garage',
                    Uri::createFromString('http://192.168.1.16/snapshot.cgi?user=anon&pwd=anon')
                ),
            ]);
        });

        $this->getContainer()->share(GetSnapshotRequestHandler::class, function () {
            return new GetSnapshotRequestHandler(
                $this->getContainer()->get(CameraRepository::class),
                $this->getContainer()->get(Client::class),
                'images/offline.jpg'
            );
        });
    }
}
