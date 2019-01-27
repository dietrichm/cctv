<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\IpifyPublicIpAddressReader;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class IpifyPublicIpAddressReaderTest extends TestCase
{
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
        $ipAddress = $this->reader->get();

        $this->markTestIncomplete();
    }
}
