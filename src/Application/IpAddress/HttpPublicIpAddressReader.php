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
            $this->logger->warning(
                'Failing to read public IP address'
            );

            throw new IpAddressReadFailed();
        }

        $responseString = (string) $response->getBody();

        try {
            $ipAddress = new IpAddress($responseString);
        } catch (InvalidArgumentException $exception) {
            $this->logger->warning(
                'Retrieved malformed public IP address',
                [
                    'ip_address' => $responseString,
                ]
            );

            throw new IpAddressReadFailed();
        }

        return $ipAddress;
    }
}
