<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\GetSnapshotRequestHandler;
use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Detroit\Cctv\Tests\CameraBuilder;
use Detroit\Cctv\Tests\CreatesRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
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

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);
        $this->httpClient = $this->createMock(Client::class);

        $this->handler = new GetSnapshotRequestHandler(
            $this->cameraRepository,
            $this->httpClient
        );
    }

    /**
     * @test
     */
    public function itReturnsImageFromSnapshotUri()
    {
        $cameraName = 'foo';

        $camera = CameraBuilder::create()
            ->build();

        $this->cameraRepository->expects($this->once())
            ->method('findByName')
            ->with($cameraName)
            ->willReturn($camera);

        $expectedResponse = new GuzzleResponse();
        $expectedResponse->getBody()->write('snapshot');

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
            $cameraName
        );

        $this->assertEquals(
            $expectedResponse->getBody(),
            $response->getBody()
        );
    }

    /**
     * @test
     */
    public function itDisablesCachingOnReturnedSnapshots()
    {
        $camera = CameraBuilder::create()
            ->build();

        $this->cameraRepository->method('findByName')
            ->willReturn($camera);

        $this->httpClient->method('request')
            ->willReturn(new GuzzleResponse());

        $response = $this->handler->__invoke(
            $this->createRequest(),
            new Response(),
            'foo'
        );

        $this->assertEquals(
            $response->getHeaderLine('Cache-Control'),
            'no-cache, no-store, must-revalidate'
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
            $cameraName
        );

        $this->assertEquals(StatusCode::NOT_FOUND, $response->getStatusCode());
        $this->assertEmpty((string) $response->getBody());
    }

    /**
     * @test
     */
    public function itThrowsWhenFailingToGetSnapshot()
    {
        $camera = CameraBuilder::create()
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

        $this->handler->__invoke(
            $this->createRequest(),
            new Response(),
            'foo'
        );
    }
}
