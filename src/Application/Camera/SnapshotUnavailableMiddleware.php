<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SnapshotUnavailableMiddleware
{
    /**
     * @var string
     */
    private $offlineImagePath;

    public function __construct(string $offlineImagePath)
    {
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
            $offlineImage = file_get_contents($this->offlineImagePath);
            $response->getBody()->write($offlineImage);

            return $response->withHeader('Content-Type', 'image/jpeg');
        }

        return $response;
    }
}
