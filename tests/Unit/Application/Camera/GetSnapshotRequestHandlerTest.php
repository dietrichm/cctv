<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\GetSnapshotRequestHandler;
use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Tests\CreatesRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use League\Uri\Uri;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;
use Teapot\StatusCode;

final class GetSnapshotRequestHandlerTest extends TestCase
{
    use CreatesRequests;

    /**
     * @var GetSnapshotRequestHandler
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
     * @var string
     */
    private $offlineImagePath;

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);
        $this->httpClient = $this->createMock(Client::class);
        $this->offlineImagePath = 'public/images/offline.jpg';

        $this->handler = new GetSnapshotRequestHandler(
            $this->cameraRepository,
            $this->httpClient,
            $this->offlineImagePath
        );
    }

    /**
     * @test
     */
    public function itReturnsImageFromSnapshotUri()
    {
        $cameraName = 'foo';

        $camera = new Camera(
            $cameraName,
            Uri::createFromString('http://example.org')
        );

        $this->cameraRepository->expects($this->once())
            ->method('findByName')
            ->with($cameraName)
            ->willReturn($camera);

        $expectedResponse = new GuzzleResponse();

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with(
                'get',
                $camera->getSnapshotUri(),
                ['timeout' => 2.0]
            )
            ->willReturn($expectedResponse);

        $response = $this->handler->__invoke(
            $this->createRequest(),
            new Response(),
            ['cameraName' => $cameraName]
        );

        $this->assertEquals(
            $expectedResponse,
            $response
        );
    }

    /**
     * @test
     */
    public function itReturnsNotFoundWhenCameraDoesNotExist()
    {
        $cameraName = 'foo';

        $this->cameraRepository->method('findByName')
            ->willThrowException(CameraNotFound::withName($cameraName));

        $this->httpClient->expects($this->never())
            ->method('request');

        $response = $this->handler->__invoke(
            $this->createRequest(),
            new Response(),
            ['cameraName' => $cameraName]
        );

        $this->assertEquals(StatusCode::NOT_FOUND, $response->getStatusCode());
        $this->assertEmpty((string) $response->getBody());
    }

    /**
     * @test
     */
    public function itReturnsOfflineImageWhenFailingToGetSnapshot()
    {
        $cameraName = 'foo';

        $camera = new Camera(
            $cameraName,
            Uri::createFromString('http://example.org')
        );

        $this->cameraRepository->method('findByName')
            ->willReturn($camera);

        $this->httpClient->method('request')
            ->willThrowException(new ClientException(
                'error',
                $this->createRequest()
            ));

        $response = $this->handler->__invoke(
            $this->createRequest(),
            new Response(),
            ['cameraName' => $cameraName]
        );

        $this->assertEquals(
            'image/jpeg',
            $response->getHeaderLine('Content-Type')
        );
        $this->assertEquals(
            file_get_contents($this->offlineImagePath),
            (string) $response->getBody()
        );
    }
}
