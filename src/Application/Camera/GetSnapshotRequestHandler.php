<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetSnapshotRequestHandler
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
        ResponseInterface $response,
        array $arguments
    ): ResponseInterface {
        $camera = $this->cameraRepository->findByName($arguments['cameraName']);

        return $response;
    }
}
