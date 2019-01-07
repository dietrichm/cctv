<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\RebootCameraHandler;
use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use GuzzleHttp\Client;
use League\Uri\Uri;
use PHPUnit\Framework\TestCase;

final class RebootCameraHandlerTest extends TestCase
{
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

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);
        $this->httpClient = $this->createMock(Client::class);

        $this->handler = new RebootCameraHandler(
            $this->cameraRepository,
            $this->httpClient
        );
    }

    /**
     * @test
     */
    public function itCallsCameraRebootUri()
    {
        $command = new RebootCameraCommand('foo');

        $camera = new Camera(
            'foo',
            Uri::createFromString('http://example.org')
        );
        $camera->setRebootUri(
            Uri::createFromString('http://example.org/reboot')
        );

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

        $camera = new Camera(
            'foo',
            Uri::createFromString('http://example.org')
        );

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
}
