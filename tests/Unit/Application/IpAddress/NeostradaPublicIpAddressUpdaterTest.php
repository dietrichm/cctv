<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\NeostradaPublicIpAddressUpdater;
use Detroit\Cctv\Domain\IpAddress\IpAddress;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class NeostradaPublicIpAddressUpdaterTest extends TestCase
{
    /**
     * @var NeostradaPublicIpAddressUpdater
     */
    private $updater;

    /**
     * @var MockObject
     */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        $this->updater = new NeostradaPublicIpAddressUpdater(
            $this->httpClient,
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
                    ],
                ]
            )
            ->willReturn(new Response());

        $this->updater->set(new IpAddress('179.26.203.196'));

        $this->markTestIncomplete();
    }
}
