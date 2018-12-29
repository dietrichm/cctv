<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetSnapshotRequestHandler
{
    /**
     * @var CameraRepository
     */
    private $cameraRepository;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(
        CameraRepository $cameraRepository,
        Client $httpClient
    ) {
        $this->cameraRepository = $cameraRepository;
        $this->httpClient = $httpClient;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments
    ): ResponseInterface {
        $camera = $this->cameraRepository->findByName($arguments['cameraName']);

        return $this->httpClient->request(
            'get',
            (string) $camera->getSnapshotUri()
        );
    }
}
