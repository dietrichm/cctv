<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\ListSnapshotsRequestHandler;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Tests\CameraBuilder;
use Detroit\Cctv\Tests\CreatesRequests;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;
use Slim\Views\Twig;

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

    /**
     * @var MockObject
     */
    private $view;

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);
        $this->view = $this->createMock(Twig::class);

        $this->handler = new ListSnapshotsRequestHandler(
            $this->cameraRepository,
            $this->view
        );
    }

    /**
     * @test
     */
    public function itRendersSnapshotsForAllCameras()
    {
        $expectedResponse = new Response();

        $cameras = [
            CameraBuilder::create()
                ->build(),
            CameraBuilder::create()
                ->build(),
        ];

        $this->cameraRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($cameras);

        $this->view->expects($this->once())
            ->method('render')
            ->with(
                $expectedResponse,
                'camera/snapshot/list.html.twig',
                [
                    'cameras' => $cameras,
                ]
            )
            ->willReturn($expectedResponse);

        $response = $this->handler->__invoke(
            $this->createRequest(),
            $expectedResponse
        );

        $this->assertEquals($expectedResponse, $response);
    }
}
