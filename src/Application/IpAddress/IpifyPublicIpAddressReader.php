<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\IpAddressReadFailed;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressReader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

final class IpifyPublicIpAddressReader implements PublicIpAddressReader
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $endpoint;

    public function __construct(
        Client $httpClient,
        string $endpoint
    ) {
        $this->httpClient = $httpClient;
        $this->endpoint = $endpoint;
    }

    public function get(): IpAddress
    {
        try {
            $response = $this->httpClient->request(
                'get',
                $this->endpoint
            );
        } catch (RequestException $exception) {
            throw new IpAddressReadFailed();
        }

        try {
            $ipAddress = new IpAddress(
                (string) $response->getBody()
            );
        } catch (InvalidArgumentException $exception) {
            throw new IpAddressReadFailed();
        }

        return $ipAddress;
    }
}
