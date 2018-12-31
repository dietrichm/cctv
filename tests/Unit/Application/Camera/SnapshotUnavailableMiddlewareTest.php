<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\SnapshotUnavailableMiddleware;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Detroit\Cctv\Tests\CreatesRequests;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

final class SnapshotUnavailableMiddlewareTest extends TestCase
{
    use CreatesRequests;

    /**
     * @var SnapshotUnavailableMiddleware
     */
    private $middleware;

    /**
     * @var string
     */
    private $offlineImagePath;

    public function setUp()
    {
        $this->offlineImagePath = 'public/images/offline.jpg';

        $this->middleware = new SnapshotUnavailableMiddleware(
            $this->offlineImagePath
        );
    }

    /**
     * @test
     */
    public function itReturnsResponseWhenAvailable()
    {
        $expectedResponse = new Response();

        $response = $this->middleware->__invoke(
            $this->createRequest(),
            $expectedResponse,
            function (
                RequestInterface $request,
                ResponseInterface $response
            ) use ($expectedResponse) {
                $this->assertEquals($expectedResponse, $response);

                return $response;
            }
        );

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function itReturnsOfflineImageWhenCameraUnavailable()
    {
        $response = $this->middleware->__invoke(
            $this->createRequest(),
            new Response(),
            function (
                RequestInterface $request,
                ResponseInterface $response
            ) {
                throw CameraUnavailable::withName('foo');
            }
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
