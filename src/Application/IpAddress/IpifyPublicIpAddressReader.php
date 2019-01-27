<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\IpAddressReadFailed;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressReader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
        try {
            $response = $this->httpClient->request(
                'get',
                'https://api.ipify.org'
            );
        } catch (RequestException $exception) {
            throw new IpAddressReadFailed();
        }

        return new IpAddress(
            (string) $response->getBody()
        );
    }
}
