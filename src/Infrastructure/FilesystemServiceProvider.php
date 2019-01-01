<?php

namespace Detroit\Cctv\Infrastructure;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

final class FilesystemServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        FilesystemInterface::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(FilesystemInterface::class, function () {
            return new Filesystem(
                new Local(__DIR__ . '/../../')
            );
        });
    }
}
