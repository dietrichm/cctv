<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\RebootCameraHandler;
use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use Detroit\Cctv\Tests\CameraBuilder;
use Detroit\Cctv\Tests\CreatesRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class RebootCameraHandlerTest extends TestCase
{
    use CreatesRequests;

    /**
     * @var RebootCameraHandler
     */
    private $handler;

    /**
     * @var MockObject
     */
    private $cameraRepository;

    /**
     * @var MockObject
     */
    private $httpClient;

    /**
     * @var MockObject
     */
    private $logger;

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);
        $this->httpClient = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->handler = new RebootCameraHandler(
            $this->cameraRepository,
            $this->httpClient,
            $this->logger
        );
    }

    /**
     * @test
     */
    public function itCallsCameraRebootUri()
    {
        $command = new RebootCameraCommand('foo');

        $camera = CameraBuilder::create()
            ->build();

        $this->cameraRepository->expects($this->once())
            ->method('findByName')
            ->with('foo')
            ->willReturn($camera);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with(
                'get',
                $camera->getRebootUri()
            );

        $this->handler->handleRebootCameraCommand($command);
    }

    /**
     * @test
     */
    public function itOnlyCallsRebootUriWhenAvailable()
    {
        $command = new RebootCameraCommand('foo');

        $camera = CameraBuilder::create()
            ->withoutRebootUri()
            ->build();

        $this->cameraRepository->method('findByName')
            ->willReturn($camera);

        $this->httpClient->expects($this->never())
            ->method('request');

        $this->handler->handleRebootCameraCommand($command);
    }

    /**
     * @test
     */
    public function itThrowsWhenCameraDoesNotExist()
    {
        $command = new RebootCameraCommand('foo');

        $exception = CameraNotFound::withName('nonexistant');

        $this->cameraRepository->method('findByName')
            ->willThrowException($exception);

        $this->httpClient->expects($this->never())
            ->method('request');

        $this->expectExceptionObject($exception);

        $this->handler->handleRebootCameraCommand($command);
    }

    /**
     * @test
     */
    public function itThrowsWhenFailingToCallRebootUri()
    {
        $command = new RebootCameraCommand('foo');

        $camera = CameraBuilder::create()
            ->withName('foo')
            ->build();

        $this->cameraRepository->method('findByName')
            ->willReturn($camera);

        $this->httpClient->method('request')
            ->willThrowException(new RequestException(
                'error',
                $this->createRequest()
            ));

        $this->expectExceptionObject(
            CameraUnavailable::withName('foo')
        );

        $this->handler->handleRebootCameraCommand($command);
    }

    /**
     * @test
     */
    public function itLogsWhenFailingToCallRebootUri()
    {
        $command = new RebootCameraCommand('foo');

        $camera = CameraBuilder::create()
            ->withName('foo')
            ->build();

        $this->cameraRepository->method('findByName')
            ->willReturn($camera);

        $this->httpClient->method('request')
            ->willThrowException(new RequestException(
                'error',
                $this->createRequest()
            ));

        $this->logger->expects($this->once())
            ->method('warning')
            ->with(
                'Could not reboot camera'
            );

        $this->expectExceptionObject(
            CameraUnavailable::withName('foo')
        );

        $this->handler->handleRebootCameraCommand($command);
    }
}
