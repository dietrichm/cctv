<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Domain\Camera\CameraRepository;
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

    /**
     * @var string
     */
    private $offlineImagePath;

    public function __construct(
        CameraRepository $cameraRepository,
        Client $httpClient,
        string $offlineImagePath
    ) {
        $this->cameraRepository = $cameraRepository;
        $this->httpClient = $httpClient;
        $this->offlineImagePath = $offlineImagePath;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments
    ): ResponseInterface {
        try {
            $camera = $this->cameraRepository->findByName($arguments['cameraName']);
        } catch (CameraNotFound $exception) {
            return $response->withStatus(StatusCode::NOT_FOUND);
        }

        try {
            return $this->httpClient->request(
                'get',
                (string) $camera->getSnapshotUri(),
                ['timeout' => 2.0]
            );
        } catch (RequestException $exception) {
            $offlineImage = file_get_contents($this->offlineImagePath);
            $response->getBody()->write($offlineImage);

            return $response->withHeader('Content-Type', 'image/jpeg');
        }
    }
}
