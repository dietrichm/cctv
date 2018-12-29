<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Uri\Uri;

final class CameraServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        CameraRepository::class,
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
    }
}
