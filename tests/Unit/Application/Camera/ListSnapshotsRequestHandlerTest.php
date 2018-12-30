<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\ListSnapshotsRequestHandler;
use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Tests\CreatesRequests;
use League\Uri\Uri;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;

final class ListSnapshotsRequestHandlerTest extends TestCase
{
    use CreatesRequests;

    /**
     * @var ListSnapshotsRequestHandler
     */
    private $handler;

    /**
     * @var MockObject
     */
    private $cameraRepository;

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);

        $this->handler = new ListSnapshotsRequestHandler(
            $this->cameraRepository
        );
    }

    /**
     * @test
     */
    public function itRendersSnapshotsForAllCameras()
    {
        $this->cameraRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Camera(
                    'foo',
                    Uri::createFromString('http://example.org/foo')
                ),
                new Camera(
                    'bar',
                    Uri::createFromString('http://example.org/bar')
                ),
            ]);

        $response = $this->handler->__invoke(
            $this->createRequest(),
            new Response()
        );

        $this->assertEquals(
            '<img src="/snapshot/foo"><img src="/snapshot/bar">',
            (string) $response->getBody()
        );
    }
}
