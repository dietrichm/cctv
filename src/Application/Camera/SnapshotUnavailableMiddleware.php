<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SnapshotUnavailableMiddleware
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var string
     */
    private $offlineImagePath;

    public function __construct(
        FilesystemInterface $filesystem,
        string $offlineImagePath
    ) {
        $this->filesystem = $filesystem;
        $this->offlineImagePath = $offlineImagePath;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        try {
            $response = $next($request, $response);
        } catch (CameraUnavailable $exception) {
            $response->getBody()->write(
                $this->filesystem->read($this->offlineImagePath)
            );

            return $response->withHeader(
                'Content-Type',
                $this->filesystem->getMimetype($this->offlineImagePath)
            );
        }

        return $response;
    }
}
