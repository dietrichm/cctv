<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\IpifyPublicIpAddressReader;
use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\IpAddressReadFailed;
use Detroit\Cctv\Tests\CreatesRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class IpifyPublicIpAddressReaderTest extends TestCase
{
    use CreatesRequests;

    /**
     * @var MockObject
     */
    private $httpClient;

    /**
     * @var IpifyPublicIpAddressReader
     */
    private $reader;

    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        $this->reader = new IpifyPublicIpAddressReader(
            $this->httpClient
        );
    }

    /**
     * @test
     */
    public function itReturnsCurrentPublicIpAddress()
    {
        $response = new Response();
        $response->getBody()->write('160.237.142.139');

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with(
                'get',
                'https://api.ipify.org'
            )
            ->willReturn($response);

        $this->assertEquals(
            new IpAddress('160.237.142.139'),
            $this->reader->get()
        );
    }

    /**
     * @test
     */
    public function itThrowsWhenFailingToGetIpAddress()
    {
        $this->httpClient->method('request')
            ->willThrowException(new RequestException(
                'error',
                $this->createRequest()
            ));

        $this->expectException(IpAddressReadFailed::class);

        $this->reader->get();
    }
}
