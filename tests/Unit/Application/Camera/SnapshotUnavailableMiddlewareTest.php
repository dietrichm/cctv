<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\SnapshotUnavailableMiddleware;
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

    public function setUp()
    {
        $this->middleware = new SnapshotUnavailableMiddleware();
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
}
