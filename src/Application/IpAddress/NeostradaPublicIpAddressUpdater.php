<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\IpAddressUpdateFailed;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressUpdater;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

final class NeostradaPublicIpAddressUpdater implements PublicIpAddressUpdater
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
        LoggerInterface $logger,
        string $apiToken,
        int $dnsId,
        int $recordId
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->apiToken = $apiToken;
        $this->dnsId = $dnsId;
        $this->recordId = $recordId;
    }

    public function set(IpAddress $ipAddress): void
    {
        try {
            $this->httpClient->request(
                'patch',
                'https://api.neostrada.com/api/dns/edit/' . $this->dnsId,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->apiToken,
                    ],
                    'form_params' => [
                        'record_id' => $this->recordId,
                        'content' => $ipAddress->getIpAddress(),
                    ],
                ]
            );
        } catch (RequestException $exception) {
            throw new IpAddressUpdateFailed();
        }
    }
}
