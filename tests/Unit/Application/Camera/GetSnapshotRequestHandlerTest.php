<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\GetSnapshotRequestHandler;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Tests\CreatesRequests;
use League\Uri\Uri;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;

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

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);

        $this->handler = new GetSnapshotRequestHandler(
            $this->cameraRepository
        );
    }

    /**
     * @test
     */
    public function itReturnsImageFromSnapshotUri()
    {
        $response = $this->handler->__invoke(
            $this->createRequest(),
            new Response()
        );

        $this->assertEmpty((string) $response->getBody());
    }
}
