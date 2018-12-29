<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SnapshotsRequestHandler
{
    /**
     * @var CameraRepository
     */
    private $cameraRepository;

    public function __construct(CameraRepository $cameraRepository)
    {
        $this->cameraRepository = $cameraRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $cameras = $this->cameraRepository->findAll();

        return $response;
    }
}
