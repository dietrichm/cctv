<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

        if (!$camera->hasRebootUri()) {
            return;
        }

        try {
            $this->httpClient->request(
                'get',
                (string) $camera->getRebootUri()
            );
        } catch (RequestException $exception) {
            throw CameraUnavailable::withName($camera->getName());
        }
    }
}
