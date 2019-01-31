<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressUpdater;
use GuzzleHttp\Client;

final class NeostradaPublicIpAddressUpdater implements PublicIpAddressUpdater
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $apiToken;

    /**
     * @var int
     */
    private $dnsId;

    /**
     * @var int
     */
    private $recordId;

    public function __construct(
        Client $httpClient,
        string $apiToken,
        int $dnsId,
        int $recordId
    ) {
        $this->httpClient = $httpClient;
        $this->apiToken = $apiToken;
        $this->dnsId = $dnsId;
        $this->recordId = $recordId;
    }

    public function set(IpAddress $ipAddress): void
    {
        $this->httpClient->request(
            'patch',
            'https://api.neostrada.com/api/dns/edit/' . $this->dnsId,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );
    }
}
