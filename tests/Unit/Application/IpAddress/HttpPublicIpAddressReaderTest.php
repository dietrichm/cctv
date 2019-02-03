<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\HttpPublicIpAddressReader;
use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\IpAddressReadFailed;
use Detroit\Cctv\Tests\CreatesRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class HttpPublicIpAddressReaderTest extends TestCase
{
    use CreatesRequests;

    /**
     * @var MockObject
     */
    private $httpClient;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var HttpPublicIpAddressReader
     */
    private $reader;

    /**
     * @var MockObject
     */
    private $logger;

    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->endpoint = 'https://example.org';

        $this->reader = new HttpPublicIpAddressReader(
            $this->httpClient,
            $this->logger,
            $this->endpoint
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
                $this->endpoint
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

    /**
     * @test
     */
    public function itLogsWhenFailingToGetIpAddress()
    {
        $this->httpClient->method('request')
            ->willThrowException(new RequestException(
                'error',
                $this->createRequest()
            ));

        $this->logger->expects($this->once())
            ->method('warning')
            ->with(
                'Failing to read public IP address'
            );

        $this->expectException(IpAddressReadFailed::class);

        $this->reader->get();
    }

    /**
     * @test
     */
    public function itThrowsWhenGettingInvalidIpAddress()
    {
        $response = new Response();
        $response->getBody()->write('160.237.142.999');

        $this->httpClient->method('request')
            ->willReturn($response);

        $this->expectException(IpAddressReadFailed::class);

        $this->reader->get();
    }

    /**
     * @test
     */
    public function itLogsWhenGettingInvalidIpAddress()
    {
        $response = new Response();
        $response->getBody()->write('160.237.142.999');

        $this->httpClient->method('request')
            ->willReturn($response);

        $this->logger->expects($this->once())
            ->method('warning')
            ->with(
                'Retrieved malformed public IP address',
                [
                    'ip_address' => '160.237.142.999',
                ]
            );

        $this->expectException(IpAddressReadFailed::class);

        $this->reader->get();
    }
}
