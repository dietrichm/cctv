<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\SnapshotUnavailableMiddleware;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Detroit\Cctv\Tests\CreatesRequests;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $offlineImagePath;

    public function setUp()
    {
        $this->filesystem = new Filesystem(new MemoryAdapter());
        $this->offlineImagePath = 'foo/bar/baz.jpg';

        $this->middleware = new SnapshotUnavailableMiddleware(
            $this->filesystem,
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
        $this->filesystem->write(
            $this->offlineImagePath,
            'test jpeg'
        );

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
            'test jpeg',
            (string) $response->getBody()
        );
    }
}
