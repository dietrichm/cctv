<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Teapot\StatusCode;

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
        string $cameraName
    ): ResponseInterface {
        try {
            $camera = $this->cameraRepository->findByName(
                $cameraName
            );
        } catch (CameraNotFound $exception) {
            return $response->withStatus(StatusCode::NOT_FOUND);
        }

        try {
            $response = $this->httpClient->request(
                'get',
                (string) $camera->getSnapshotUri(),
                ['timeout' => 3.0]
            );
        } catch (RequestException $exception) {
            throw CameraUnavailable::withName($camera->getName());
        }

        return $response->withHeader(
            'Cache-Control',
            'no-cache, no-store, must-revalidate'
        );
    }
}
