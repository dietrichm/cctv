<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

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

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        CameraRepository $cameraRepository,
        Client $httpClient,
        LoggerInterface $logger
    ) {
        $this->cameraRepository = $cameraRepository;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
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
            $this->logger->warning('Could not reboot camera', [
                'camera' => $camera,
                'exception' => $exception,
            ]);

            throw CameraUnavailable::withName($camera->getName());
        }
    }
}
