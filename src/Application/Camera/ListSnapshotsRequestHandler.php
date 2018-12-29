<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListSnapshotsRequestHandler
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
        $output = implode('', array_map(function (Camera $camera) {
            return '<img src="/snapshot/' . $camera->getName() . '">';
        }, $this->cameraRepository->findAll()));

        $response->getBody()->write($output);

        return $response;
    }
}
