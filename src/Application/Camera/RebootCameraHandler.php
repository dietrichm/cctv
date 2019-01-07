<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use GuzzleHttp\Client;

final class RebootCameraHandler
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

    public function handleRebootCameraCommand(RebootCameraCommand $command)
    {
        $camera = $this->cameraRepository->findByName(
            $command->getName()
        );

        $this->httpClient->request(
            'get',
            (string) $camera->getRebootUri()
        );
    }
}
