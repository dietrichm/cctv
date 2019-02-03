<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\NeostradaPublicIpAddressUpdater;
use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\IpAddressUpdateFailed;
use Detroit\Cctv\Tests\CreatesRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class NeostradaPublicIpAddressUpdaterTest extends TestCase
{
    use CreatesRequests;

    /**
     * @var NeostradaPublicIpAddressUpdater
     */
    private $updater;

    /**
     * @var MockObject
     */
    private $httpClient;

    /**
     * @var MockObject
     */
    private $logger;

    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->updater = new NeostradaPublicIpAddressUpdater(
            $this->httpClient,
            $this->logger,
            'api-token',
            303,
            808
        );
    }

    /**
     * @test
     */
    public function itUpdatesIpAddressOnDnsRecord()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with(
                'patch',
                'https://api.neostrada.com/api/dns/edit/303',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer api-token',
                    ],
                    'form_params' => [
                        'record_id' => 808,
                        'content' => '179.26.203.196',
                    ],
                ]
            )
            ->willReturn(new Response());

        $this->updater->set(new IpAddress('179.26.203.196'));
    }

    /**
     * @test
     */
    public function itThrowsWhenFailingToUpdateIpAddress()
    {
        $this->httpClient->method('request')
            ->willThrowException(new RequestException(
                'error',
                $this->createRequest()
            ));

        $this->expectException(IpAddressUpdateFailed::class);

        $this->updater->set(new IpAddress('179.26.203.196'));
    }

    /**
     * @test
     */
    public function itLogsWhenFailingToUpdateIpAddress()
    {
        $this->httpClient->method('request')
            ->willThrowException(new RequestException(
                'error',
                $this->createRequest()
            ));

        $this->logger->expects($this->once())
            ->method('warning')
            ->with(
                'Failing to update public IP address',
                [
                    'ip_address' => '179.26.203.196',
                ]
            );

        $this->expectException(IpAddressUpdateFailed::class);

        $this->updater->set(new IpAddress('179.26.203.196'));
    }
}
