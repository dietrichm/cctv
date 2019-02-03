<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\IpAddressReadFailed;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressReader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

final class HttpPublicIpAddressReader implements PublicIpAddressReader
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $endpoint;

    public function __construct(
        Client $httpClient,
        LoggerInterface $logger,
        string $endpoint
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
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
