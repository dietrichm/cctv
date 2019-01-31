<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\NeostradaPublicIpAddressUpdater;
use GuzzleHttp\Client;
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
}
