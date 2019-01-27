<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressReader;
use GuzzleHttp\Client;

final class IpifyPublicIpAddressReader implements PublicIpAddressReader
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get(): IpAddress
    {
        $response = $this->httpClient->request(
            'get',
            'https://api.ipify.org'
        );

        return new IpAddress(
            (string) $response->getBody()
        );
    }
}
